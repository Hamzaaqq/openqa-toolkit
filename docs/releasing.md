# Release checklist

Before publishing v0.1.0:

- Confirm the current main commit passes all CI jobs, including consumer installation.
- Run the tutorial on Windows and Linux where possible; report which platforms were verified.
- Review the changelog and change `unreleased` to the actual release date.
- Create a GitHub release and tag `v0.1.0` from the verified commit. Describe limitations in release notes.
- Sign in to Packagist and submit https://github.com/Hamzaaqq/openqa-toolkit. Enable GitHub auto-updates if desired.
- Verify `composer require --dev hamzaaqq/openqa-toolkit:^0.1` from a fresh project, then test the binary.

Do not claim publication, downloads or adoption before they are verified. Never purchase artificial traffic, stars or contributions.
