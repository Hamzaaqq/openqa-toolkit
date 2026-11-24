# Reusable GitHub Action

This composite action checks a local HTML file after you check out your repository. Set up PHP with DOM and cURL first. Until a release tag is published, @main is usable for evaluation; pin to a reviewed full commit SHA for production.

```yaml
name: HTML quality
on: [push, pull_request]
permissions:
  contents: read
jobs:
  audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: dom, curl
          coverage: none
      - uses: Hamzaaqq/openqa-toolkit@main
        id: openqa
        with:
          file: public/index.html
          fail-on-findings: 'true'
      - uses: actions/upload-artifact@v4
        if: ${{ always() && steps.openqa.outputs.report-path != '' }}
        with:
          name: openqa-report
          path: ${{ steps.openqa.outputs.report-path }}
```

Replace public/index.html with your generated or checked-in HTML file. The action does not build your site. Outputs are issue-count and report-path. The report is a temporary file on the same runner; upload it only if it contains no confidential information. Set fail-on-findings to false for advisory mode; operational errors always fail. The action performs no network requests and is currently tested on Linux runners. Use ordinary push/pull_request workflows with minimal permissions; never execute untrusted checkout code in privileged workflows.
