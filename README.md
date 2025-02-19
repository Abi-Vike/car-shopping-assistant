# Car Shopping Assistant

An AI-powered car shopping assistant tailored for the Ethiopian market, using Laravel, Vue.js, Laravel Nova, and the Gemini API to provide efficient, embedding-based searches for buyers and sellers.
![Search Cars](https://github.com/user-attachments/assets/74ab053f-ac32-46d4-9985-92cd8c2272f1)
![Dashboard](https://github.com/user-attachments/assets/2f74f57e-fd80-4321-8e6c-808547fa9845)
![User List](https://github.com/user-attachments/assets/f85ce2db-862f-41a6-aebe-19b31acda7fa)
![Car List](https://github.com/user-attachments/assets/be8ee841-c24d-4615-81e3-4a0675badb22)



## Overview

This project was inspired by a LinkedIn post from [Joseph Kibur](https://www.linkedin.com/in/josephkibur/), who proposed an AI-powered Car Shopping Assistant to streamline the car buying process by handling queries on fuel type, seating capacity, budget, and availability. Taking this idea as a challenge, I developed a prototype in just 2 hours with the help of ChatGPT, creating a full-stack web application that enables users to search for cars in the Ethiopian market efficiently using advanced AI embeddings.

The platform allows car owners to list their vehicles, buyers to search based on various criteria (e.g., location, fuel type), and administrators to manage users and inventory via a dashboard. It leverages the Gemini API for semantic search, making it scalable and relevant for users.

## Why I Built This Project

I built this project to address the tedious and time-consuming process of car shopping, particularly in the Ethiopian market, where buyers often struggle to find vehicles that match their needs (e.g., suitable for local road conditions, affordable in Ethiopian Birr, and available in specific cities like Addis Ababa or Dire Dawa). [Joseph Kibur’s](https://www.linkedin.com/in/josephkibur/) idea highlighted the potential for AI to simplify this process, and I saw an opportunity to create a practical solution while deepening my skills in full-stack development and AI integration.

## Tech Stack

- **Backend**: Laravel (for API development, business logic, and routing), Laravel Nova (for admin dashboard)
- **Frontend**: Vue.js (for interactive user interface), Tailwind CSS (for styling and responsive design)
- **AI Integration**: Gemini API (for generating embeddings to enable semantic search)
- **Database**: MySQL (via Laravel Eloquent ORM)
- **HTTP Client**: Guzzle (for API requests to Gemini, with SSL verification disabled for development)

## Problems Faced and Solutions

### 1. Performance Issues

**Problem**: Initial searches using embeddings were slow, especially when processing large datasets or generating embeddings for every car in real-time. The cosine similarity calculations and database queries also caused delays.

**Solution**:
- **Pre-Generated Embeddings**: I modified the `Car` model to generate and store embeddings immediately after a car is created or updated using the `updateEmbedding` method, reducing runtime overhead.
- **Database Optimization**: Ensured the `embedding` field was cast as an array in the model and used efficient Eloquent queries to fetch only necessary data.
- **Caching**: Implemented Laravel caching for frequently accessed metrics (e.g., total cars, users by role) to minimize database hits.
- **Lazy Loading**: In the frontend, I optimized Vue components to load search results only when needed, improving responsiveness.

### 2. Embedding with Gemini API

**Problem**: Integrating the Gemini API for embeddings was challenging due to SSL certificate issues (`cURL error 60`) and incorrect request formats (`INVALID_ARGUMENT` errors). The API sometimes returned unexpected response structures, and ensuring embeddings were arrays (not strings) caused errors in the `cosineSimilarity` method.

**Solution**:
- **SSL Issue**: Disabled SSL verification (`verify => false`) in Guzzle for development, but planned a long-term fix by updating CA certificates on my system. For production, I’ll configure Guzzle to use a trusted CA bundle.
- **Request Format**: Adjusted the `generateEmbedding` method to match the Gemini API’s `text-embedding-004:embedContent` endpoint, ensuring the request body included the correct `model`, `content`, and `parts` structure. I added robust error handling and logging to diagnose issues.
- **Data Type Handling**: Cast the `embedding` field in the `Car` model as an array using Laravel’s `$casts` property and updated the `cosineSimilarity` method to handle potential string inputs by converting them to arrays, ensuring `array_map` worked correctly.
- **Testing**: Used realistic car descriptions in the `CarFactory` to test embeddings, ensuring searches like “Toyota Corolla in Addis Ababa” returned relevant results.

## How to Use

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/matakltm-code/car-shopping-assistant.git
   cd car-shopping-assistant
   ```

2. **Install Dependencies**:

   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:

   - Copy `.env.example` to `.env` and update the following:
     - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for your MySQL database.
     - `GEMINI_API_KEY` for the Gemini API (obtain from Google Cloud Console).

4. **Migrate and Seed Database**:

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Run the Application**:

   ```bash
   php artisan serve
   npm run dev
   ```

6. **Access the Dashboard**:

   - Visit `http://car-shopping-assistant.test/admin` to see metrics.
   - Use the frontend at `http://car-shopping-assistant.test` to search for cars.

## Directory Structure

- `app/`: Contains models, controllers, and services (e.g., `Car`, `User`, `DashboardController`).
- `resources/`: Includes views (e.g., `dashboard.blade.php`), Vue components (e.g., `CarSearch.vue`), and CSS (e.g., Tailwind).
- `database/`: Migrations, seeders, and factories (e.g., `CarFactory`).
- `routes/`: API and web routes.
- `public/`: Compiled assets (CSS, JS).


## Contact

For questions or collaboration, reach out to me at:

- **Name**: Micheal Ataklt
- **Position**: Fullstack Developer (Freelancing)
- **Phone**: 0913833334
