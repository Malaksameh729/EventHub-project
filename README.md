# 🎟️ EventHub

> A Laravel-based RESTful API for discovering, managing, and booking events.

EventHub is an event management and booking platform that connects attendees with events while supporting event organizers, speakers, sponsors, categories, bookings, tickets, QR codes, location-based discovery, and in-app notifications.

---

## ✨ Features

### 🔐 Authentication & Authorization

- User registration and login
- Token-based API authentication
- Role-based access control
- Protected API endpoints
- Multiple user roles:
  - Attendee
  - Organizer
  - Speaker
  - Sponsor
  - Admin

### 🎫 Event Management

- Create, update, delete, and retrieve events
- Event categories
- Free and paid events
- Online and in-person events
- Event image upload
- Event capacity management
- Start and end dates
- Venue and address information
- Latitude and longitude coordinates

### 🔎 Search, Filtering & Sorting

Events can be filtered by:

- Keyword
- City / Location
- Event type
- Price type
- Category slug

Events can also be sorted by:

- Nearest upcoming event
- Lowest price
- Nearest date + lowest price

Example:

GET /api/v1/events?slug=tech&sort=lowest_price
### 📍 Location-Based Events

EventHub supports location-based event discovery using latitude and longitude.

Example:

GET /api/v1/events?latitude=30.0444&longitude=31.2357&radius=50

The API calculates the distance between the user's location and available events.

### 🏠 Home

The Home API provides:

- Event categories
- Upcoming events
- Nearby events

### 🎟️ Booking & Tickets

Users can:

- Book events
- Choose ticket quantity
- Update existing bookings
- Re-activate cancelled bookings
- Cancel bookings
- View their tickets
- Generate QR codes for confirmed bookings

Booking information includes:

- Booking code
- Event information
- Ticket count
- Booking status
- QR code

### 🎤 Speaker Applications

Users with the Speaker role can submit speaker applications.

Speaker applications can contain:

- Event
- Session title
- Session summary
- Duration
- Session format

### 🤝 Sponsorship

Sponsors can select sponsorship packages for specific events.

Each sponsorship is connected to:

- Sponsor / User
- Event
- Package
- Price

Packages can contain benefits such as:

- Logo size
- Booth
- Speaking slot
- Tickets
- ### 🔔 Notifications

EventHub provides in-app notifications for important actions such as:

- Booking confirmation
- Booking updates
- Booking re-activation
- Event updates

Notifications can contain:

- Title
- Message
- Event image
- Event ID
- Type
- Read / Unread status

Example:

{
    "id": 194,
    "title": "Booking Confirmed",
    "message": "Your booking is confirmed. You can find your tickets in Tickets section.",
    "image": "events/example.png",
    "read": 0,
    "type": "booking",
    "event_id": 73
}

---

## 🛠️ Tech Stack

| Technology | Usage |
|---|---|
| PHP | Backend language |
| Laravel | REST API framework |
| MySQL | Database |
| Eloquent ORM | Database relationships and queries |
| Laravel API Resources | API response formatting |
| Laravel Sanctum / API Tokens | Authentication |
| Spatie Permission | Roles & Permissions |
| Composer | PHP dependency management |
| Git & GitHub | Version control |
| Postman | API testing |

---

## 📋 Requirements

Make sure you have the following installed:

- PHP 8.2+
- Composer
- MySQL
- Git
- XAMPP, Laragon, or another Laravel-compatible local server

---
## 📡 API Endpoints

### Authentication

Protected endpoints require an authenticated user's access token.

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
Content-Type: application/json
```

### Authentication Endpoints

```http
POST /api/v1/register
POST /api/v1/login
POST /api/v1/logout
```

### Events

```http
GET    /api/v1/events
GET    /api/v1/events/{id}
POST   /api/v1/events
PUT    /api/v1/events/{id}
DELETE /api/v1/events/{id}
```

### Event Filtering

```http
GET /api/v1/events?keyword=AI
GET /api/v1/events?city=Cairo
GET /api/v1/events?type=online
GET /api/v1/events?price_type=free
GET /api/v1/events?slug=tech
```

### Event Sorting

```http
GET /api/v1/events?sort=nearest
GET /api/v1/events?sort=lowest_price
GET /api/v1/events?sort=nearest_price
```

### Location Filtering

```http
GET /api/v1/events?latitude=30.0444&longitude=31.2357&radius=50
```

### Bookings

```http
POST /api/v1/bookings
GET /api/v1/my-tickets
```

### Notifications

```http
GET /api/v1/notifications
```

---

## 📍 Location Data

Each physical event can store geographical coordinates:

- Latitude
- Longitude

Example:

```text
latitude: 30.0444
longitude: 31.2357
```

These coordinates are used for:

- Distance calculation
- Nearby event discovery
- Location-based filtering

---

## 🗃️ Main Relationships

```text
User
 ├── Bookings
 │      └── Event
 │
 ├── Notifications
 │      └── Event
 │
 ├── Speaker Applications
 │      └── Event
 │
 └── Sponsorships
        ├── Event
        └── Package

Event
 └── Category
```

---

## 🔄 Booking Flow

```text
User
  ↓
Select Event
  ↓
Choose Tickets
  ↓
Check Event Capacity
  ↓
Create / Update Booking
  ↓
Generate Booking Code
  ↓
Generate QR Code
  ↓
Create Notification
```

---

## 🧩 Services

### EventService

Responsible for:

- Event filtering
- Keyword search
- Category filtering
- Location-based distance calculation
- Event sorting

### BookingService

Responsible for:

- Creating bookings
- Updating bookings
- Capacity validation
- Booking codes
- QR code generation
- Ticket retrieval

### NotificationService

Responsible for:

- Creating notifications
- Connecting notifications to events
- Storing notification images
- Storing notification types

---

## 📁 Project Structure

```text
eventhub/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── API/
│   │   └── Resources/
│   │
│   ├── Models/
│   │
│   └── Services/
│       ├── EventService.php
│       ├── BookingService.php
│       └── NotificationService.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── storage/
│   └── app/
│       └── public/
│           └── events/
│
├── resources/
├── public/
├── config/
├── .env.example
├── composer.json
├── artisan
└── README.md
```

---

## 🧪 API Testing

The API can be tested using:

- Postman
- Insomnia
- Thunder Client

For protected endpoints, include:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
```

Example:

```http
GET http://127.0.0.1:8000/api/v1/events
```

Location example:

```http
GET http://127.0.0.1:8000/api/v1/events?latitude=30.0444&longitude=31.2357&radius=50
```

---

## 🔒 Security

EventHub uses authentication and authorization to protect user-specific operations.

Examples include:

- Protected booking endpoints
- Role-restricted speaker applications
- Role-restricted sponsorship actions
- Event management permissions
- User-specific notifications
- User-specific bookings

Users are only allowed to manage resources they are authorized to access.

---

## 🚧 Future Improvements

- 💳 Online payment integration
- 📲 Push notifications
- ⭐ Event reviews and ratings
- 📅 Calendar integration
- 📊 Advanced analytics dashboard
- 📍 Advanced location recommendations
- 🎤 Speaker management dashboard
- 🤝 Sponsor management dashboard
- 🎟️ Event attendance / check-in system
- ⚡ Real-time notifications
- 🤖 Personalized event recommendations

---

## 🤝 Contributing

Contributions are welcome.

### 1. Fork the repository

### 2. Create a feature branch

```bash
git checkout -b feature/your-feature
```

### 3. Make your changes

### 4. Commit your changes

```bash
git add .
git commit -m "Add your feature"
```

### 5. Push your branch

```bash
git push origin feature/your-feature
```

### 6. Open a Pull Request

---

## 📄 License

This project is developed for educational and practical purposes.

---

## ⭐ EventHub

EventHub provides a complete backend foundation for an event discovery and booking platform, bringing together:

**Attendees • Organizers • Speakers • Sponsors**

in one centralized system.

Built using **Laravel & MySQL**.
