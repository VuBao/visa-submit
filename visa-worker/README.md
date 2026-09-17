# Visa submission Worker

This Worker receives the existing visa form's multipart request, validates the two applicant fields and each required document, uploads files privately to Google Drive, then appends one row to Google Sheets. It has no PHP, MariaDB, D1, R2, applicant login, draft, or admin dashboard.

## Google setup

1. Create a Google Sheet with columns `submission_id`, `submitted_at`, `full_name`, `company_name`, `drive_files`.
2. Create a private Drive folder owned by the Google account that will authorize OAuth. Do not enable public link access.
3. Create a Google OAuth client and authorize Drive + Sheets once to obtain a refresh token.
4. Deploy the Worker and set secrets (values are never committed):

```sh
wrangler secret put GOOGLE_OAUTH_CLIENT_ID
wrangler secret put GOOGLE_OAUTH_CLIENT_SECRET
wrangler secret put GOOGLE_OAUTH_REFRESH_TOKEN
wrangler secret put GOOGLE_SHEET_ID
wrangler secret put GOOGLE_DRIVE_FOLDER_ID
```

The Worker temporarily supports the previous service-account secrets as a fallback, but OAuth is required for a personal My Drive because service accounts have no Drive storage quota.

5. Set the Pages form `data-endpoint` to the Worker URL (or route it through the same origin).
