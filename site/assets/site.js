document.querySelectorAll('.code').forEach((block) => {
  const button = block.querySelector('button');
  const status = block.querySelector('[role="status"]');
  if (!navigator.clipboard || !button) return;
  button.hidden = false;
  button.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(block.querySelector('code').textContent);
      status.textContent = 'Code copied.';
      button.textContent = 'Copied';
    } catch {
      status.textContent = 'Copy failed. Select the code to copy manually.';
      button.textContent = 'Copy failed';
    }
  });
});
