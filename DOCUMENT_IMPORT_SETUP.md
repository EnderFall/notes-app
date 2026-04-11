# Document Import Feature Setup

## Overview
The notes app now supports importing text from PDF and Word documents without saving the files to the server. The text is extracted and inserted into the note content area for editing.

## Requirements

### 1. Install Composer Dependencies
Run this command in the `beholf` folder:
```bash
cd c:\xampp\htdocs\notes-app\beholf
composer install
```

Or if dependencies are already installed, update them:
```bash
composer update
```

This will install:
- **smalot/pdfparser** - For extracting text from PDF files
- **phpoffice/phpword** - For extracting text from Word documents (already installed)

### 2. Create Writable Temp Directory
The app needs a temporary directory to process uploaded files:
```bash
mkdir c:\xampp\htdocs\notes-app\beholf\writable\uploads\temp
```

Or create it manually with write permissions (777).

## How It Works

### User Flow:
1. User clicks "Import from Document" and selects a PDF or Word file
2. File is uploaded via AJAX to `/rapat/extract_document_text`
3. Server extracts text from the document
4. Text is inserted into the note content textarea
5. Temporary file is deleted immediately
6. User can edit the extracted text before saving

### Features:
- ✅ Supports PDF (.pdf), Word (.doc, .docx)
- ✅ No files saved to server
- ✅ Text extracted in HTML format with paragraphs
- ✅ Works in both Add and Edit note forms
- ✅ Shows progress spinner during extraction
- ✅ Appends to existing content or creates new

### Security:
- Only logged-in users can upload
- File type validation (PDF/Word only)
- Temporary files deleted immediately after extraction
- Upload logged in activity log

## Testing

1. Go to **Notes > Add Note**
2. Fill in basic details
3. Click "Choose File" under "Import from Document"
4. Select a PDF or Word file
5. Wait for extraction (spinner shows progress)
6. Check that text appears in the content textarea
7. Edit as needed and save

## Troubleshooting

### "Failed to extract text from document"
- Make sure composer dependencies are installed
- Check PHP extensions: `php -m | findstr -i zip`
- Check error logs: `beholf/writable/logs/`

### "Could not extract text from document"
- File might be a scanned PDF (image-based, no text)
- Word document might be corrupted
- Try a different file

### Permission errors
- Ensure `writable/uploads/temp/` has write permissions
- On Windows: Right-click folder > Properties > Security > Edit
- Give full control to current user

## File Locations

- **AJAX Endpoint**: `beholf/app/Controllers/Rapat.php::extract_document_text()`
- **Text Extraction**: `beholf/app/Controllers/Rapat.php::extractTextFromDocument()`
- **Add Form**: `beholf/app/Views/rapat/tambah_rapat.php`
- **Edit Form**: `beholf/app/Views/rapat/edit_rapat.php`
- **Temp Files**: `beholf/writable/uploads/temp/` (auto-deleted)
