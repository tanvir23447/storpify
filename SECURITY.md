# SIS AI SWAP STUDIO — SECURITY & PRIVACY SPECIFICATION

## 1. Data Isolation & Privacy
- **Private-by-Default:** Uploads and generated media are stored under segregated storage IDs (`storage/uploads/{user_id}/` and `storage/outputs/{user_id}/`).
- **Path Traversal Protection:** All file paths are strictly sanitized with UUID-based identifiers; raw client filenames are never used for disk storage paths.
- **MIME & Magic Header Validation:** File headers are validated using Python `PIL` / magic bytes to prevent malicious file uploads.

## 2. Authentication & Authorization
- **JWT Session Tokens:** HMAC-SHA256 tokens with configurable expiry.
- **Password Security:** PBKDF2-HMAC-SHA256 with cryptographically random salts.
- **RBAC:** Roles: `super_admin`, `admin`, `user`. Admin actions (system settings, engine management, audit logs) require administrative scopes.

## 3. Safe AI Execution
- Memory limits and VRAM monitoring prevent denial of service from excessive image resolution.
- Tiled inference prevents GPU/CPU out-of-memory errors on large images.
