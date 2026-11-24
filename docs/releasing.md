# Release checklist

The one-time v0.1.0 GitHub Actions release workflow verifies core tests, Laravel integration and the composite action before publishing the tag and release from the tested main commit. Only the publishing job has write permission. Existing releases are not overwritten.

After publication:
- Verify the release and tag on the GitHub Releases page.
- Sign in to Packagist and submit https://github.com/Hamzaaqq/openqa-toolkit.
- Verify composer require --dev hamzaaqq/openqa-toolkit:^0.1 in a fresh project.
- Collect Windows installation feedback; automated release verification runs on Linux.
- Share the project in relevant communities under their posting rules and ask for genuine technical feedback.

Do not claim downloads, adoption or publication before they are verified. Never purchase artificial traffic, stars or contributions.
