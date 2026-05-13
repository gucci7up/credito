INSERT INTO settings (`key`, `value`) VALUES
('business_name', 'Mi Negocio'),
('currency', 'RD$'),
('itbis_enabled', '0'),
('itbis_percent', '18'),
('default_discount', '0'),
('footer_text', '¡Gracias por su compra!'),
('logo_path', '')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

