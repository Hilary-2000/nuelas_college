# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Ladybird College Management Information System (CMIS)** — a PHP-based college management platform for Kenyan institutions, covering academics, finance, HR, boarding, transport, and SMS notifications.

## Writing Style (Strict)

**Avoid em dashes in content written into the system.** This includes UI labels, descriptions, placeholders, button text, help text, notices, error/success messages, seed or sample data, and any other text that ends up stored or displayed in the application. Only use an em dash when listing items or when stating and defining something (e.g., naming a term and then explaining what it is). Do not use it to join two independent clauses in ordinary prose; use a period, comma, colon, semicolon, or parentheses for that instead.

## Branch / College Mapping

This repository serves three distinct colleges, each on its own git branch. Each branch may have a different feature set, UI layout, and module limitations tailored to that college.

| Branch | College | Working directory |
|---|---|---|
| `main` | Nuelas College | `/opt/lampp/htdocs/nuelas_college` |
| `lawrenzo` | Lawrenzo College | `/opt/lampp/htdocs/nuelas_college` (same dir, different branch) |
| `lizola_college` | Lizola College | `/opt/lampp/htdocs/nuelas_college` (same dir, different branch) |

**Important:** Always confirm which branch (college) is checked out before making changes. Features, layouts, and module availability differ per branch — a change that is correct for one college may not apply to another. Do not merge college-specific changes across branches without explicit instruction.

### Keeping CLAUDE.md in sync across branches

`CLAUDE.md` lives in the repo and should be identical across all three branches. After updating it on one branch, carry it to the others:

```bash
# From whichever branch you just updated CLAUDE.md on:
git stash                          # stash any other uncommitted work if needed

git checkout lawrenzo
git checkout main -- CLAUDE.md    # pull the file from main (adjust source branch as needed)
git checkout main -- .claude/main_db.sql
git checkout main -- .claude/school_db.sql

git checkout lizola_college
git checkout main -- CLAUDE.md
git checkout main -- .claude/main_db.sql
git checkout main -- .claude/school_db.sql
```

Only copy `CLAUDE.md` and `.claude/` this way — never cherry-pick or merge code changes across branches without explicit instruction.

## Git / GitHub Rules

- **Never commit or push automatically.** Always wait for an explicit instruction ("commit this", "push to GitHub", "create a PR") before running any `git commit`, `git push`, or `gh pr create` command.
- This rule applies even after completing a task — finishing a feature does not imply permission to commit.
- When committing is requested, stage only the specific files related to the change. Never use `git add -A` or `git add .` without listing what will be included.
- Some changes are intentionally shared across all three branches (e.g. CLAUDE.md, schema files, shared utilities). Ask which branches should receive the commit before pushing.

## Running the Application

This is a LAMPP-hosted PHP application with no build step.

```bash
# Start LAMPP (Apache + MySQL)
sudo /opt/lampp/lamppctl.sh start

# Check which college (branch) is active
git branch --show-current

# Switch to a college branch
git checkout main           # Nuelas College
git checkout lawrenzo       # Lawrenzo College
git checkout lizola_college # Lizola College

# Access via browser
http://localhost/nuelas_college
```

Entry points:
- `index.php` — public landing page
- `login.php` — authenticated login

There is no test suite, linter, or CI configuration.

## Architecture

### Request Flow

1. Browser loads a page (e.g., `/academics/subjects.php`)
2. Page renders HTML, includes common header/nav, and makes AJAX calls
3. AJAX calls hit handlers under `/ajax/<module>/` which perform DB operations and return JSON
4. Role-based access is enforced via `$_SESSION['authority']` (levels 1–5)

### Multi-Tenancy

Each institution has its own MySQL database. The master database `ladybird_smis` stores user accounts and maps each school to its database name. On login, the school's database name is stored in `$_SESSION['databasename']` and used by `conn2.php` to switch context dynamically.

### Key Files

| File | Purpose |
|---|---|
| `connections/conn1.php` | Master DB connection (`ladybird_smis`) — user auth |
| `connections/conn2.php` | School-specific DB connection — uses `$_SESSION['databasename']` |
| `main_pages/functions.php` | Shared utilities; `e()` is the HTML-escaping helper |
| `ajax/login/` | Authentication, session setup, role resolution |
| `ajax/academic/`, `ajax/finance/`, etc. | AJAX handlers per module |

### Frontend

Bootstrap 5 (FlexStart template) with jQuery. JS is split by domain:
- `assets/JS/dashboardajax.js` — dashboard data loading
- `assets/JS/academics.js`, `finance.js` — module-specific logic

### PDF / Spreadsheet Generation

- PDFs use a bundled FPDF library (in `/reports`)
- Excel exports use `phpoffice/phpspreadsheet` (installed via Composer)

```bash
composer install   # restore vendor/ after cloning
```

### Third-Party Integrations

- **M-Pesa** — `/financepages/mpesa_transaction.php`
- **SMS** — `/sms_apis/` and `/ajax/sms/`
- **Google Analytics** — embedded in page templates

## Database

### Schema reference files

The canonical schema lives in two files inside `.claude/`:

| File | Database | Purpose |
|---|---|---|
| `.claude/main_db.sql` | `ladybird_smis` | Master DB — shared across all colleges |
| `.claude/school_db.sql` | `nuelas_college` (representative) | Per-college DB — each college has its own copy |

**Keep these files up to date.** Whenever a `CREATE TABLE`, `ALTER TABLE`, or `DROP TABLE` change is made to either database, update the corresponding `.claude/*.sql` file to reflect the new structure. These files are the source of truth for understanding the schema in future sessions.

### Connections

- `connections/conn1.php` — connects to `ladybird_smis`
- `connections/conn2.php` — connects to the college DB named in `$_SESSION['databasename']`
- Credentials: `root` / *(no password)* on `localhost`
- All queries use MySQLi prepared statements with `bind_param`

### Master DB tables (`ladybird_smis`)

Core tables for platform-level management:

| Table | Purpose |
|---|---|
| `user_tbl` | All user accounts across colleges |
| `school_information` | College registry — maps each college to its database |
| `requested_user` | College signup/onboarding requests |
| `developers` | Developer portal accounts |
| `sms_api` | Platform-level SMS API config |
| `settings` | Global platform settings |
| `verification_code` | Login/auth verification codes |
| `client_inquiries` | Public contact/inquiry form submissions |
| `pesapal_payments` | PesaPal payment gateway records |
| `timetable_req` | Timetable generation requests |
| `updated_files` | File update tracking |
| `user_feedback` | User-submitted feedback |
| `email_address` | Sent email log |

### Per-college DB tables

Each college DB (e.g. `nuelas_college`, `lawrenzo_college`, `lizola_college`) has the same base schema. Key tables:

| Table | Module |
|---|---|
| `student_data` | Student profiles and admission |
| `finance` | Fee payments and balances |
| `fees_structure` | Fee schedule per course/year |
| `mpesa_transactions` | M-Pesa payment records |
| `payroll_information` | Staff payroll |
| `salary_payment` | Salary disbursement records |
| `advance_pay` | Staff salary advances |
| `expenses` / `expense_category` | Expenditure tracking |
| `school_revenue` | Revenue records |
| `exams_tbl` / `exam_record_tbl` / `exams_cat` / `exam_cat_scores` / `exam_unit` | Examinations |
| `attendancetable` | Student attendance |
| `table_subject` | Subjects/units |
| `course_unit_assignment` | Course-to-unit mapping |
| `teacher_unit_assignment` | Lecturer-to-unit mapping |
| `class_teacher_tbl` | Class teacher assignments |
| `assignments` | Student assignments |
| `lesson_plan` | Lesson plans |
| `academic_calendar` | Term/semester dates |
| `apply_leave` / `leave_categories` | Staff leave management |
| `boarding_list` / `dorm_list` / `hostel_rooms` | Boarding management |
| `discipline_incidents` / `discipline_warning` | Discipline records |
| `transport_enrolled_students` / `van_routes` / `school_vans` | Transport |
| `sms_table` / `sms_api` / `template_messages` | SMS notifications |
| `tblnotification` / `message_n_alert` | In-app notifications |
| `logs` | System activity log |
| `library_details` / `book_circulation` / `library_notifications` | Library |
| `asset_table` / `suppliers` / `supplier_bills` / `supplier_bill_payments` | Asset & procurement |
| `settings` | College-level settings |

## Module Layout

```
/academics        — courses, subjects, timetables, exams
/administration   — staff, student registration, class registers, HR, leaves
/boarding_pages   — dorms, discipline, boarding enrollment
/transport        — routes, student transport enrollment
/financepages     — fees, payroll, expenses, assets, M-Pesa, receipts
/reports          — PDF report generation (FPDF)
/ajax             — all AJAX backend handlers (mirrors module structure)
/dashboard        — role-specific dashboard pages
/main_pages       — settings, notifications, user profiles
/csv              — CSV import/export
/sms_apis         — SMS gateway integrations
```

## Coding Conventions

- HTML escaping: always use `e($value)` from `functions.php` when rendering user-supplied data
- Database writes: use MySQLi prepared statements — never interpolate variables into SQL strings
- Sessions: check `$_SESSION['authority']` for role before any privileged operation
- AJAX responses: return JSON with a `status` key (`success` / `error`)
