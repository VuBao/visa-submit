# Visa submission Worker

This Worker validates the two required applicant fields, uploads optional files privately to Google Drive, and writes reporting data to Google Sheets. Cloudflare D1 stores the application code, hashed six-digit PIN, Drive folder, and uploaded-document state so applicants can reopen an application and add documents later without email or a user account.

## Google setup

1. Create a Google Sheet with columns `submission_id`, `submitted_at`, `full_name`, `company_name`, `drive_files`.
2. Create a private Drive folder and share it with the Service Account as an Editor. Do not enable public link access.
3. Share the Google Sheet with that same Service Account as an Editor.
4. Deploy the Worker and set secrets (values are never committed):

```sh
wrangler secret put GOOGLE_OAUTH_CLIENT_ID
wrangler secret put GOOGLE_OAUTH_CLIENT_SECRET
wrangler secret put GOOGLE_OAUTH_REFRESH_TOKEN
wrangler secret put GOOGLE_SHEET_ID
wrangler secret put GOOGLE_DRIVE_FOLDER_ID
```

The Worker normally uses the OAuth refresh token for Google Drive and Sheets. If that token is invalid, revoked, or expires, it automatically retries with the Service Account. This keeps the application available as long as the Service Account remains an Editor of the target Sheet and private Drive folder.

5. Set the Pages form `data-endpoint` to the Worker URL (or route it through the same origin).

## Incident runbook: Google OAuth `invalid_grant`

### Symptom

The Admin application displays `Admin API error.` and the identity label remains at `Đang xác thực…`. Worker logs contain `Google OAuth authentication failed (400): invalid_grant`.

### Cause

The configured Google OAuth refresh token is no longer valid, commonly because it was revoked, expired, or its OAuth consent configuration changed. This is an integration credential problem, not a Cloudflare Access login failure.

### Protection in this project

- `accessToken()` tries OAuth first, then automatically falls back to the configured Service Account.
- The Service Account has only the required access: Editor to the private Drive folder and target Google Sheet.
- Worker logs record the fallback but never expose a credential to the client.

### Recovery and verification

1. Confirm the Service Account is still an Editor of both Google resources.
2. Refresh the Admin page and verify `GET /api/admin/applications` returns data.
3. If both credential paths fail, rotate/re-enter only the affected Cloudflare Worker secret and re-test with a non-production test submission.
4. Never put the private key, OAuth refresh token, Sheet ID, or Drive folder ID in Git, browser code, or client-visible errors.

## Incident runbook: mobile browser shows `Load failed`

`Load failed` is a browser network failure, so it does not prove whether the Worker saved the application. Before asking the applicant to resubmit, search the Admin list for the applicant and company and check the document count. The form now creates the application with a small request, then sends one file per request. It retains a random submission ID in the browser session until it receives the application code and PIN; a repeated creation request with that ID recovers the existing application. After a file request loses its response, the form reopens the application to check whether that file was saved. If the connection is still unavailable, the applicant receives their application code and PIN and can inspect the saved documents before sending the remaining files.
