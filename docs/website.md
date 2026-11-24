# Documentation website

The static site lives in site/. Preview it locally with python -m http.server 8000 --directory site and open http://localhost:8000.

Run python tests/site_check.py and php bin/openqa html site/index.html to verify basic structure. The Pages workflow checks all four HTML pages and deploys only from main after successful checks.

To enable GitHub Pages, open repository Settings > Pages and choose GitHub Actions as the build and deployment source. Run the Website checks and Pages deployment workflow from the Actions tab if necessary. The expected public URL is https://hamzaaqq.github.io/openqa-toolkit/.

SEO metadata, a sitemap and robots.txt are included. Search engine indexing is not guaranteed. The site uses no third-party tracking scripts or external font assets.
