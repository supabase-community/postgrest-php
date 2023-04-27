# Supabase `postgrest-php` examples 

Examples of how to interact with the `postgrest-php` library.

```
.
├── call-a-postgres-fucntion
│   ├── bulk-processing.php
│   ├── call-a-postgres-function-with-arguments.php
│   ├── call-a-postgres-function-with-filters.php
│   └── call-a-postgres-function-without-arguments.php
├── delete-data
│   └── delete-records.php
├── fetch-data
│   ├── getting-your-data.php
│   ├── filtering-through-foreign-tables.php
│   ├── query-foreign-tables-join.php
│   ├── query-same-foreign-tables-multiple-times.php
│   ├── querying-foreign-table-with-count.php
│   ├── querying-json-data.php
│   ├── querying-with-count-option.php
│   ├── select-foreign-tables.php
│   └── sign-in-with-whatsApp-OTP.php
├── insert-data
│   ├── bulk-create.php
│   ├── create-a-record-and-return-it.php
│   └── create-a-record.php
├── update-data
│    ├── update-a-record-and-return-it.php
│    ├── updating-json.data.php
│    └── updating-your-data.php
├── upsert-data 
│    ├── bulk-upsert-your-data.php
│    ├── upsert-your-data.php
│    └── upserting-into-tables-with-constraints.php
├── using-filters
│   ├── applying-filters.php
│   ├── chaining.php
│   ├── conditional-chaining.php
│   ├── filter-by-values-within-a-JSON-column.php
│   └── select-foreing-tables.php
└── using-modifiers
    ├── limit-the-query-on-a-foreign-table.php
    ├── limit-the-query-to-a-range.php
    ├── limit-the-query-with-select.php
    ├── order-the-query-with-select.php
    ├── retrive-the-query-as-0-1-rows.php
    ├── retrive-the-query-as-a-CSV-string.php
    ├── retrive-the-query-as-one-row.php
    ├── select-the-query.php
    └── set-an-abort-signal.php

```

## Setup
Clone the repository locally.

Install the dependencies `composer install` 

### Setup the Env
To obtain the API Access Details, please sign into your Supabase account. 

```
cp .env.example examples/.env
```

#### For the `REFERENCE_ID`
Once signed on to the dashboard, navigate to, Project >> Project Settings >> General settings. Copy the Reference ID for use in the `.env`.

#### For the `API_KEY`
Once signed on to the dashboard, navigate to, Project >> Project Settings >> API >> Project API keys. Choose either the `anon` `public` or the `service_role` key.

Populate the `examples/.env` to include `REFERENCE_ID` and `API_KEY`.

## Running Examples

```
cd examples
php fetch-data/fetch-data.php
```
