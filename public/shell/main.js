const output = document.getElementById('output');
const form = document.getElementById('prompt');
const input = document.getElementById('input');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const cmd = input.value.trim();
  if (!cmd) return;

  print(`TARIA> ${cmd}`);
  input.value = '';

  try {
    const res = await fetch('/api/command', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ command: cmd })
    });

    const data = await res.json();
    print(data.output ?? '[no output]');
  } catch (err) {
    print('Command failed');
  }
});

function print(text) {
  output.textContent += text + '\n';
  output.scrollTop = output.scrollHeight;
}
