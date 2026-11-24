# Contributing

Small, reproducible contributions are welcome.

1. Open an issue describing the problem and a minimal HTML fixture.
2. Make a focused change and add a regression check in tests/run.php.
3. Run php tests/run.php and include results in the pull request.

Never include secrets or private customer data. Test only authorized systems. Favor deterministic offline tests; live requests must be opt-in.
