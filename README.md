Static export of the MilkWise front-end for GitHub Pages

To deploy this static demo on GitHub Pages:

1. Create a GitHub repository (or use an existing one).
2. Copy the contents of the `static-site` folder into the repo root (or push this folder and set Pages to the `static-site` folder in settings).

Simple push commands (replace `<username>` and `<repo>`):

```bash
cd static-site
git init
git remote add origin https://github.com/<username>/<repo>.git
git checkout -b gh-pages
git add .
git commit -m "Deploy static front-end"
git push -u origin gh-pages --force
```

3. In the repository `Settings -> Pages`, set Branch to `gh-pages` and save.
4. Your site will be available at: `https://<username>.github.io/<repo>/`

Notes:
- This is a static demo. Server-side features (login, database, admin actions) are not functional here.
- For the full dynamic site, deploy the original PHP project to a PHP-capable host and configure `config/secrets.php` and the database using `db.sql`.
