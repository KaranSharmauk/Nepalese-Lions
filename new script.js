function isStrongPassword(password) {
  const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return strongPasswordRegex.test(password);
}

// Open a modal (e.g., Create Account or Forgot Password)
function openModal(id) {
  document.getElementById(id).style.display = 'block';
}

// Close a modal
function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}

function createAccount() {
  const email = document.getElementById('newEmail').value.trim();
  const password = document.getElementById('newPassword').value.trim();

  if (!email || !password || !email.includes('@')) {
    alert('Enter a valid email and password.');
    return;
  }
  if (!isStrongPassword(password)) {
    alert('Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.');
    return;
  }
  if (users[email]) {
    alert('Account already exists.');
    return;
  }

  users[email] = btoa(password);
  localStorage.setItem('users', JSON.stringify(users));
  alert('Account created successfully!');
  closeModal('createAccountModal');
}

// Handle user login
function handleLogin() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
  
    if (users[email] && users[email] === password) {
      alert('Login successful!');
      // Hide login section and show the dashboard sections
      document.getElementById('login-section').style.display = 'none';
      showAllSections();
      alert('Login successful!');
    } else {
      alert('Invalid email or password.');
    }
  }

  // Reset a user's password
function resetPassword() {
  const email = document.getElementById('resetEmail').value.trim();
  if (users[email]) {
    const newPassword = prompt('Enter your new password:');
    if (newPassword && isStrongPassword(newPassword)) {
      users[email] = btoa(newPassword);
      localStorage.setItem('users', JSON.stringify(users));
      alert('Password reset successfully!');
      closeModal('forgotPasswordModal');
    } else {
      alert('Password does not meet security requirements.');
    }
  } else {
    alert('Email not found!');
  }
}

// Show all sections after successful login
function showAllSections() {
    document.getElementById('programme').classList.remove('hidden');
    document.getElementById('map').classList.remove('hidden');
    document.getElementById('todo-list').classList.remove('hidden');
    document.getElementById('nearby-places').classList.remove('hidden');
    document.getElementById('other-locations').classList.remove('hidden');
  }
  
  // Set a reminder for an event
  function setReminder(event) {
    reminders.push(event);
    updateReminderList();
  }
  
  // Update the reminders list in the To-Do List section
  function updateReminderList() {
    const list = document.getElementById('reminder-list');
    list.innerHTML = reminders.map((item, index) => `
      <li>
        ${item} 
        <button class="btn" onclick="removeReminder(${index})">Remove</button>
      </li>
    `).join('');
  }
  
  // Remove a reminder
  function removeReminder(index) {
    reminders.splice(index, 1);
    updateReminderList();
  }
  