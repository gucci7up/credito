# Subir este proyecto a GitHub

Repositorio destino: https://github.com/gucci7up/credito.git

## Opción A (recomendada): usando Git en tu PC
1. Instala Git (si no lo tienes): https://git-scm.com/download/win
2. Abre una terminal (PowerShell o Git Bash) en la carpeta del proyecto.
3. Ejecuta:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/gucci7up/credito.git
git push -u origin main
```

> Nota: **no subas tu `.env`** (ya está en `.gitignore`). En GitHub usa un secreto/variable o crea tu `.env` en el servidor.

## Opción B: sin Git (subida manual)
1. Entra al repo en GitHub → **Add file** → **Upload files**
2. Arrastra y suelta el contenido del proyecto (carpetas `controllers/`, `models/`, `views/`, `public/`, etc.)
3. Haz commit desde la web.

