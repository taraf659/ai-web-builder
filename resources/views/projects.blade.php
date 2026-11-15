@extends('layouts.app')

@section('title', 'Projects - AI Web Builder')

@section('content')
<div class="projects-header">
  <h1 class="page-title">Your Projects</h1>
  <p class="page-subtitle">Manage and organize all your AI-generated websites</p>
  <button class="btn-primary" id="addProjectBtn">
    <i class='bx bx-plus'></i>
    Create New Project
  </button>
</div>

<div class="projects-grid" id="projectsGrid">
  <div class="empty-state">
    <div class="empty-icon">
      <i class='bx bx-folder-open'></i>
    </div>
    <h3>No Projects Yet</h3>
    <p>Start building your first website with AI assistance. Click the button above to get started!</p>
    <button class="btn-secondary" id="startBuildingBtn">
      <i class='bx bx-rocket'></i>
      Start Building
    </button>
  </div>
</div>

<!-- Project Template (hidden by default) -->
<div class="project-card-template" style="display: none;">
  <div class="project-card">
    <div class="project-header">
      <h3 class="project-title">Project Title</h3>
      <div class="project-status">
        <span class="status-badge status-draft">Draft</span>
      </div>
    </div>
    <div class="project-description">
      <p>Project description goes here...</p>
    </div>
    <div class="project-meta">
      <span class="project-date">
        <i class='bx bx-calendar'></i>
        Created: <span class="date">Today</span>
      </span>
      <span class="project-type">
        <i class='bx bx-code-alt'></i>
        Type: <span class="type">Website</span>
      </span>
    </div>
    <div class="project-actions">
      <button class="btn-icon edit-btn" title="Edit Project">
        <i class='bx bx-edit'></i>
      </button>
      <button class="btn-icon preview-btn" title="Preview">
        <i class='bx bx-show'></i>
      </button>
      <button class="btn-icon delete-btn" title="Delete">
        <i class='bx bx-trash'></i>
      </button>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* ChatGPT-like code blocks for projects modal */
.modal-content pre {
  background: #f6f8fa;
  border: 1px solid #d1d9e0;
  border-radius: 6px;
  padding: 16px;
  margin: 8px 0;
  overflow-x: auto;
  font-size: 14px;
  line-height: 1.45;
  position: relative;
  max-height: 400px;
  overflow-y: auto;
}

.modal-content code {
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
  background: transparent;
  border: 0;
  padding: 0;
  margin: 0;
  word-wrap: normal;
  white-space: pre;
}

/* Copy button styling for modal */
.copy-code-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  background: #f6f8fa;
  border: 1px solid #d1d9e0;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 12px;
  color: #656d76;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 4px;
  transition: all 0.2s ease;
  opacity: 0;
}

.modal-content pre:hover .copy-code-btn {
  opacity: 1;
}

.copy-code-btn:hover {
  background: #0969da;
  color: white;
  border-color: #0969da;
}

.copy-code-btn.copied {
  background: #238636;
  color: white;
  border-color: #238636;
}

.copy-code-btn i {
  font-size: 14px;
}

/* Language label for modal */
.code-language-label {
  position: absolute;
  top: 8px;
  left: 8px;
  background: #656d76;
  color: white;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

  .code-language {
    font-size: 0.75rem;
  }
}
</style>
@endpush

@push('scripts')
<script>
const addProjectBtn = document.getElementById('addProjectBtn');
const startBuildingBtn = document.getElementById('startBuildingBtn');
const projectsGrid = document.getElementById('projectsGrid');

// Handle create new project buttons
addProjectBtn.addEventListener('click', function() {
  window.location.href = '{{ route('home') }}';
});

startBuildingBtn.addEventListener('click', function() {
  window.location.href = '{{ route('home') }}';
});

// Sample project data (in a real app, this would come from the backend)
const sampleProjects = [
  {
    title: "Portfolio Website",
    description: "A modern portfolio website showcasing creative work and projects.",
    status: "completed",
    date: "2024-01-15",
    type: "Portfolio"
  },
  {
    title: "E-commerce Store",
    description: "Online store for selling handmade crafts and artwork.",
    status: "in-progress",
    date: "2024-01-10",
    type: "E-commerce"
  },
  {
    title: "Blog Platform",
    description: "Personal blog for sharing thoughts and tutorials.",
    status: "draft",
    date: "2024-01-08",
    type: "Blog"
  }
];

// Function to create project card
function createProjectCard(project) {
  const template = document.querySelector('.project-card-template');
  const card = template.cloneNode(true);
  card.style.display = 'block';
  card.className = 'project-card';

  // Update content
  card.querySelector('.project-title').textContent = project.title;
  card.querySelector('.project-description p').textContent = project.description;
  card.querySelector('.status-badge').textContent = project.status.replace('-', ' ');
  card.querySelector('.status-badge').className = 'status-badge status-' + project.status;
  card.querySelector('.date').textContent = new Date(project.date).toLocaleDateString();
  card.querySelector('.type').textContent = project.type;

  // Add event listeners
  card.querySelector('.edit-btn').addEventListener('click', () => {
    const msg = 'Editing project: ' + project.title;
    alert(msg);
  });

  card.querySelector('.preview-btn').addEventListener('click', () => {
    const msg = 'Previewing project: ' + project.title;
    alert(msg);
  });

  card.querySelector('.delete-btn').addEventListener('click', () => {
    const msg = 'Are you sure you want to delete "' + project.title + '"?';
    if (confirm(msg)) {
      card.remove();
    }
  });

  return card;
}

// Helper function to escape HTML
function escapeHtml(unsafe) {
  if (!unsafe) return '';
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// Load projects from API
async function loadProjects() {
  try {
    const response = await fetch('/api/projects');
    const data = await response.json();

    if (data.success && data.projects.length > 0) {
      // Hide empty state
      document.querySelector('.empty-state').style.display = 'none';

      // Add projects
      data.projects.forEach(project => {
        const card = createProjectCard(project);
        projectsGrid.appendChild(card);
      });
    }
  } catch (error) {
    console.error('Error loading projects:', error);
  }
}

// Create project card from API data
function createProjectCard(project) {
  const template = document.querySelector('.project-card-template');
  const card = template.cloneNode(true);
  card.style.display = 'block';
  card.classList.remove('project-card-template');

  // Set project data
  card.querySelector('.project-title').textContent = project.name;
  card.querySelector('.project-description p').textContent = project.description || 'AI-generated website project';
  card.querySelector('.date').textContent = new Date(project.created_at).toLocaleDateString();
  card.querySelector('.type').textContent = project.technology_stack ? project.technology_stack.join(', ') : 'Website';

  // Set status badge
  const statusBadge = card.querySelector('.status-badge');
  statusBadge.textContent = project.status.charAt(0).toUpperCase() + project.status.slice(1);
  statusBadge.className = 'status-badge status-' + project.status;

  // Add event listeners
  card.querySelector('.edit-btn').addEventListener('click', () => {
    showProjectModal(project);
  });

  card.querySelector('.preview-btn').addEventListener('click', () => {
    previewProject(project);
  });

  card.querySelector('.delete-btn').addEventListener('click', () => {
    const msg = 'Are you sure you want to delete this project?';
    if (confirm(msg)) {
      deleteProject(project.id, card);
    }
  });

  return card;
}

// Helper function to create code tab content
function createCodeTab(language, projectId, code, isActive) {
  const tabContent = document.createElement('div');
  tabContent.className = 'tab-content' + (isActive ? ' active' : '');
  tabContent.dataset.tab = language;
  
  const pre = document.createElement('pre');
  const codeEl = document.createElement('code');
  codeEl.id = language + '-code-' + projectId;
  codeEl.className = 'language-' + (language === 'js' ? 'javascript' : language);
  codeEl.textContent = code; // Use textContent instead of innerHTML for safety
  pre.appendChild(codeEl);
  
  const label = document.createElement('span');
  label.className = 'code-language-label';
  label.textContent = language === 'js' ? 'JavaScript' : language.toUpperCase();
  
  const copyBtn = document.createElement('button');
  copyBtn.className = 'copy-code-btn';
  copyBtn.dataset.codeId = language + '-code-' + projectId;
  copyBtn.innerHTML = '<i class="bx bx-copy"></i> Copy code';
  
  tabContent.appendChild(pre);
  tabContent.appendChild(label);
  tabContent.appendChild(copyBtn);
  
  return tabContent;
}

// Show project details modal
function showProjectModal(project) {
  // Create modal structure using DOM methods (safer than innerHTML)
  const modal = document.createElement('div');
  modal.className = 'project-modal';
  
  const modalContent = document.createElement('div');
  modalContent.className = 'modal-content';
  
  // Modal Header
  const modalHeader = document.createElement('div');
  modalHeader.className = 'modal-header';
  const title = document.createElement('h3');
  title.textContent = project.name;
  const closeBtn = document.createElement('button');
  closeBtn.className = 'close-btn';
  closeBtn.innerHTML = '&times;';
  modalHeader.appendChild(title);
  modalHeader.appendChild(closeBtn);
  
  // Modal Body
  const modalBody = document.createElement('div');
  modalBody.className = 'modal-body';
  
  // Code Tabs
  const codeTabs = document.createElement('div');
  codeTabs.className = 'code-tabs';
  ['html', 'css', 'js'].forEach((lang, index) => {
    const btn = document.createElement('button');
    btn.className = 'tab-btn' + (index === 0 ? ' active' : '');
    btn.dataset.tab = lang;
    btn.textContent = lang === 'js' ? 'JavaScript' : lang.toUpperCase();
    codeTabs.appendChild(btn);
  });
  
  // Code Content
  const codeContent = document.createElement('div');
  codeContent.className = 'code-content';
  
  // HTML Tab
  const htmlTab = createCodeTab('html', project.id, project.html_code || 'No HTML code available', true);
  const cssTab = createCodeTab('css', project.id, project.css_code || 'No CSS code available', false);
  const jsTab = createCodeTab('js', project.id, project.js_code || 'No JavaScript code available', false);
  
  codeContent.appendChild(htmlTab);
  codeContent.appendChild(cssTab);
  codeContent.appendChild(jsTab);
  
  modalBody.appendChild(codeTabs);
  modalBody.appendChild(codeContent);
  
  // Modal Footer
  const modalFooter = document.createElement('div');
  modalFooter.className = 'modal-footer';
  const copyHtmlBtn = document.createElement('button');
  copyHtmlBtn.className = 'btn-secondary copy-btn';
  copyHtmlBtn.dataset.code = 'html';
  copyHtmlBtn.textContent = 'Copy HTML';
  const copyCssBtn = document.createElement('button');
  copyCssBtn.className = 'btn-secondary copy-btn';
  copyCssBtn.dataset.code = 'css';
  copyCssBtn.textContent = 'Copy CSS';
  const copyJsBtn = document.createElement('button');
  copyJsBtn.className = 'btn-secondary copy-btn';
  copyJsBtn.dataset.code = 'js';
  copyJsBtn.textContent = 'Copy JS';
  const downloadBtn = document.createElement('button');
  downloadBtn.className = 'btn-primary download-btn';
  downloadBtn.textContent = 'Download All';
  
  modalFooter.appendChild(copyHtmlBtn);
  modalFooter.appendChild(copyCssBtn);
  modalFooter.appendChild(copyJsBtn);
  modalFooter.appendChild(downloadBtn);
  
  // Assemble modal
  modalContent.appendChild(modalHeader);
  modalContent.appendChild(modalBody);
  modalContent.appendChild(modalFooter);
  modal.appendChild(modalContent);
  
  document.body.appendChild(modal);

  // Copy code buttons (inside code blocks)
  modal.querySelectorAll('.copy-code-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const codeId = btn.getAttribute('data-code-id');
      copyCodeBlock(codeId);
    });
  });

  // Tab switching
  modal.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;
      modal.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      modal.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      btn.classList.add('active');
      modal.querySelector('.tab-content[data-tab="' + tab + '"]').classList.add('active');
    });
  });

  // Copy buttons (footer buttons - keep for backward compatibility)
  modal.querySelectorAll('.copy-btn[data-code]').forEach(btn => {
    btn.addEventListener('click', () => {
      const codeType = btn.dataset.code;
      const code = codeType === 'html' ? project.html_code :
                   codeType === 'css' ? project.css_code : project.js_code;
      if (code) {
        navigator.clipboard.writeText(code).then(() => {
          btn.textContent = 'Copied!';
          setTimeout(() => btn.textContent = 'Copy ' + codeType.toUpperCase(), 2000);
        });
      }
    });
  });

  // Download button
  modal.querySelector('.download-btn').addEventListener('click', () => {
    downloadProjectFiles(project);
  });

  // Close modal
  modal.querySelector('.close-btn').addEventListener('click', () => {
    modal.remove();
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.remove();
  });
}

// Preview project in new window
function previewProject(project) {
  if (!project.html_code) {
    alert('No HTML code available for preview');
    return;
  }

  const previewWindow = window.open('', '_blank');
  const doc = previewWindow.document;
  
  doc.open();
  doc.write('<!DOCTYPE html>');
  doc.write('<html lang="en">');
  doc.write('<head>');
  doc.write('<meta charset="UTF-8">');
  doc.write('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
  doc.write('<title>Preview<\/title>');
  if (project.css_code) {
    doc.write('<style>');
    doc.write(project.css_code);
    doc.write('<\/style>');
  }
  doc.write('<\/head>');
  doc.write('<body>');
  doc.write(project.html_code);
  if (project.js_code) {
    const scriptTag = '<' + 'script>';
    const scriptCloseTag = '<' + '/script>';
    doc.write(scriptTag);
    doc.write(project.js_code);
    doc.write(scriptCloseTag);
  }
  doc.write('<\/body>');
  doc.write('<\/html>');
  doc.close();
}

// Download project files
function downloadProjectFiles(project) {
  const zip = new JSZip();

  if (project.html_code) {
    zip.file('index.html', project.html_code);
  }
  if (project.css_code) {
    zip.file('styles.css', project.css_code);
  }
  if (project.js_code) {
    zip.file('script.js', project.js_code);
  }

  zip.generateAsync({ type: 'blob' }).then(content => {
    const link = document.createElement('a');
    link.href = URL.createObjectURL(content);
    link.download = project.name.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.zip';
    link.click();
  });
}

// Delete project
async function deleteProject(projectId, cardElement) {
  try {
    const response = await fetch('/api/projects/' + projectId, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (response.ok) {
      cardElement.remove();
      // Show empty state if no projects left
      if (document.querySelectorAll('.project-card:not(.project-card-template)').length === 0) {
        document.querySelector('.empty-state').style.display = 'block';
      }
    }
  } catch (error) {
    console.error('Error deleting project:', error);
    alert('Error deleting project');
  }
}

// Copy code block to clipboard (ChatGPT style)
function copyCodeBlock(codeId) {
  const codeElement = document.getElementById(codeId);
  const copyBtn = codeElement.parentElement.querySelector('.copy-code-btn');

  if (codeElement) {
    // Unescape HTML entities to get the actual code
    const rawCode = codeElement.textContent.replace(/&lt;/g, '<').replace(/&gt;/g, '>');

    navigator.clipboard.writeText(rawCode).then(function() {
      // Change button text to show success
      const originalHTML = copyBtn.innerHTML;
      copyBtn.innerHTML = '<i class="bx bx-check"></i> Copied!';
      copyBtn.classList.add('copied');

      // Reset after 2 seconds
      setTimeout(() => {
        copyBtn.innerHTML = originalHTML;
        copyBtn.classList.remove('copied');
      }, 2000);
    }).catch(function(err) {
      console.error('Failed to copy: ', err);
      // Fallback for older browsers
      const textArea = document.createElement('textarea');
      textArea.value = rawCode;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand('copy');
      document.body.removeChild(textArea);

      const originalHTML = copyBtn.innerHTML;
      copyBtn.innerHTML = '<i class="bx bx-check"></i> Copied!';
      copyBtn.classList.add('copied');

      setTimeout(() => {
        copyBtn.innerHTML = originalHTML;
        copyBtn.classList.remove('copied');
      }, 2000);
    });
  }
}
</script>
@endpush
