# High-Level Architecture Diagram - SiLADATA

Berikut adalah kode Mermaid untuk **Diagram Arsitektur Sistem** aplikasi SiLADATA yang dapat Anda salin dan tempel di **draw.io** (pilih menu *Arrange -> Insert -> Advanced -> Mermaid*).

```mermaid
graph TD
  %% Definisi Gaya
  classDef client fill:#E2E8F0,stroke:#718096,stroke-width:2px;
  classDef routing fill:#FEEBC8,stroke:#DD6B20,stroke-width:1.5px;
  classDef controller fill:#E6FFFA,stroke:#319795,stroke-width:1.5px;
  classDef model fill:#EBF4FF,stroke:#3182CE,stroke-width:1.5px;
  classDef storage fill:#E9D8FD,stroke:#805AD5,stroke-width:1.5px;

  %% Nodes
  Browser["Browser Client (Pengguna)"]:::client
  
  subgraph Laravel [Server Aplikasi Laravel MVC]
    Route["Route Web (routes/web.php)"]:::routing
    Middleware{"Auth & Role Middleware"}:::routing
    
    subgraph Controllers [Controllers]
      HomeCtrl["HomeController"]:::controller
      AdminCtrl["Admin Controllers"]:::controller
      PertiCtrl["Perti Controllers"]:::controller
      UnitCtrl["UnitKerja Controllers"]:::controller
    end
    
    Models["Eloquent Models"]:::model
    PDFEngine["PDF Report Engine"]:::model
  end
  
  Database[("Basis Data (MySQL / SQLite)")]:::storage
  LocalStorage["Storage Lokal (/public/uploads)"]:::storage
  GoogleDrive["Google Drive (Cloud Link)"]:::storage

  %% Aliran Request
  Browser -->|HTTP Request| Route
  Route --> Middleware
  
  %% Middleware Routing
  Middleware -->|role:admin| AdminCtrl
  Middleware -->|role:perti| PertiCtrl
  Middleware -->|role:unit_kerja| UnitCtrl
  Route -->|Akses Publik| HomeCtrl
  
  %% Logika Controller ke Model
  AdminCtrl --> Models
  PertiCtrl --> Models
  UnitCtrl --> Models
  
  %% Relasi Database
  Models <-->|Eloquent ORM| Database
  
  %% Penyimpanan Berkas
  UnitCtrl -->|Upload File| LocalStorage
  UnitCtrl -->|Simpan Tautan| GoogleDrive
  
  %% Output File PDF
  AdminCtrl --> PDFEngine
  PertiCtrl --> PDFEngine
  UnitCtrl --> PDFEngine
  PDFEngine -->|HTTP Response (Unduh PDF)| Browser
```
