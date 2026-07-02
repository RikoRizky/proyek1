# Entity-Relationship Diagram (ERD) - SiLADATA

Berikut adalah kode Mermaid untuk **Entity-Relationship Diagram (ERD)** basis data SiLADATA yang dapat Anda salin dan tempel di **draw.io** (pilih menu *Arrange -> Insert -> Advanced -> Mermaid*).

```mermaid
erDiagram
  USERS {
    bigint id PK
    varchar name
    varchar email UK
    varchar role "admin / perti / unit_kerja"
    varchar password
    varchar profile_photo_path
    bigint perti_id FK
    timestamp created_at
    timestamp updated_at
  }

  MODULES {
    bigint id PK
    varchar name
    text description
    int sort_order
    timestamp created_at
    timestamp updated_at
  }

  REQUIREMENTS {
    bigint id PK
    bigint module_id FK
    varchar title
    text description
    int sort_order
    timestamp created_at
    timestamp updated_at
  }

  SUBMISSIONS {
    bigint id PK
    bigint requirement_id FK
    bigint user_id FK
    varchar file_path
    varchar original_filename
    varchar mime_type
    bigint file_size
    varchar status "pending / uploaded"
    int version
    boolean is_latest
    json google_drive_links
    json files
    timestamp created_at
    timestamp updated_at
  }

  DISCUSSIONS {
    bigint id PK
    varchar nama
    varchar email
    varchar whatsapp
    varchar perusahaan
    varchar jabatan
    json kebutuhan
    text kebutuhan_lainnya
    varchar sistem_saat_ini
    varchar investasi
    timestamp created_at
    timestamp updated_at
  }

  %% Relasi
  USERS ||--o{ USERS : "memiliki anak (perti_id -> id)"
  MODULES ||--o{ REQUIREMENTS : "memiliki"
  REQUIREMENTS ||--o{ SUBMISSIONS : "memiliki"
  USERS ||--o{ SUBMISSIONS : "mengunggah"
```
