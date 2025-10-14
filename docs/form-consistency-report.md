Vehicle Form Consistency Report

Summary

- Goal: Align all Filament resource forms to the Vehicle Form's structure and UX.

1) Vehicle Form analysis
- Layout: uses Groups and Sections; "Basic Information", "Identifiers", "Details".
- Columns: sections commonly use 2 columns; long inputs (markdown) use columnSpanFull.
- Field types: TextInput, Select (relationship), DateTimePicker, MarkdownEditor.
- Labelling: mixture of lowercase field names (e.g., 'vin') and capitalized labels in Section using TextInput::make('Brand') (note: VehicleForm sometimes uses capitalized keys).
- Required fields are marked with ->required().

2) Template created
- `app/Filament/Resources/Shared/Schemas/FormTemplate.php` introduced to centralize:
  - Section creation with default columns
  - Group wrapper helper
  - labeledText helper to set label and required

3) Implemented changes (examples)
- `Users/Schemas/UserForm.php`
  - Reorganized fields into "User information" section using `FormTemplate`.
  - Ensured consistent labels (Full name, Email address) and required markers.

- `VehicleRequests/Schemas/VehicleRequestForm.php`
  - Split into "Request details", "Trip", and "Approval" sections.
  - Moved description and approval_note to columnSpanFull.
  - Standardized 'belongsTo' label to 'Owner' via `labeledText`.

- `VehicleDocuments/Schemas/VehicleDocumentForm.php`
  - Grouped document fields into "Document details" and "Dates & ownership" sections.
  - Standardized label names and required flags.

- `Warnings/Schemas/WarningForm.php`
  - Introduced "Warning details" and "Additional" sections.
  - Description textarea set to full width, consistent label usage.

4) Plan for remaining resources
- Apply the template across all remaining `app/Filament/Resources/**/Schemas/*Form.php` files.
- For each form:
  - Group related fields into sections (2-column layout), long textareas full width.
  - Ensure relationship selects use ->relationship('relation','name') where appropriate.
  - Standardize label naming (Title Case, clear wording).
  - Add tooltips/help text where ambiguous fields exist.

5) Next steps
- Continue refactoring the remaining forms (we already updated 4 as examples).
- Run PHP linter / static analysis and run tests to catch syntax issues.
- Manual QA in Filament UI to review spacing, labels, and tooltips.
- Iterate based on feedback.

Notes
- I intentionally created a small helper rather than a large rewrite to minimize risk.
- Some forms use numeric-only fields for user IDs and 'belongsTo' — keep or convert to relationship selects depending on model setup.

If you'd like, I can continue and apply the same refactor to all resource forms automatically, then run tests/linting. Alternatively, I can produce a PR that updates all forms in smaller batches for review.