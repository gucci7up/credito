<?php

class SettingsController extends BaseController
{
    public function indexAction(): void
    {
        $model = new Setting();
        $errors = [];
        $success = false;

        if ($this->requestMethod() === 'POST') {
            $businessName = trim((string)$this->post('business_name', ''));
            $currency = trim((string)$this->post('currency', ''));
            $footerText = trim((string)$this->post('footer_text', ''));
            $itbisEnabled = $this->post('itbis_enabled') ? '1' : '0';
            $itbisPercent = trim((string)$this->post('itbis_percent', '0'));
            $defaultDiscount = trim((string)$this->post('default_discount', '0'));

            if ($businessName === '') $errors[] = 'El nombre del negocio es obligatorio.';
            if ($currency === '') $errors[] = 'La moneda es obligatoria.';

            if (!is_numeric($itbisPercent) || (float)$itbisPercent < 0 || (float)$itbisPercent > 100) {
                $errors[] = 'El porcentaje ITBIS debe ser numérico entre 0 y 100.';
            }
            if (!is_numeric($defaultDiscount) || (float)$defaultDiscount < 0) {
                $errors[] = 'El descuento por defecto debe ser numérico y >= 0.';
            }

            // Logo (opcional)
            $logoPath = $model->get('logo_path', '');
            if (isset($_FILES['logo']) && is_array($_FILES['logo']) && ($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $uploadErr = (int)($_FILES['logo']['error'] ?? UPLOAD_ERR_OK);
                if ($uploadErr !== UPLOAD_ERR_OK) {
                    $errors[] = 'Error al subir el logo.';
                } else {
                    $tmp = (string)$_FILES['logo']['tmp_name'];
                    $name = (string)$_FILES['logo']['name'];
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowed = ['png','jpg','jpeg','webp','gif'];
                    if (!in_array($ext, $allowed, true)) {
                        $errors[] = 'Formato de logo no permitido. Usa PNG/JPG/WEBP/GIF.';
                    } else {
                        $destDir = __DIR__ . '/../public/uploads/logo';
                        if (!is_dir($destDir)) {
                            @mkdir($destDir, 0777, true);
                        }
                        $fileName = 'logo_' . date('Ymd_His') . '.' . $ext;
                        $dest = $destDir . '/' . $fileName;
                        if (!move_uploaded_file($tmp, $dest)) {
                            $errors[] = 'No se pudo guardar el logo.';
                        } else {
                            $logoPath = 'uploads/logo/' . $fileName;
                        }
                    }
                }
            }

            if (!$errors) {
                $model->setMany([
                    'business_name' => $businessName,
                    'currency' => $currency,
                    'footer_text' => $footerText,
                    'itbis_enabled' => $itbisEnabled,
                    'itbis_percent' => (string)(float)$itbisPercent,
                    'default_discount' => (string)(float)$defaultDiscount,
                    'logo_path' => $logoPath,
                ]);
                $success = true;
            }
        }

        $settings = $model->getAll();
        $this->view('settings/index', [
            'settings' => $settings,
            'errors' => $errors,
            'success' => $success,
        ]);
    }
}

