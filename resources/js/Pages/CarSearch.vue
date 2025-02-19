<template>
    <div class="h-full flex items-center justify-center bg-gray-100 py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Find Your Car in Ethiopia</h1>

            <form @submit.prevent="searchCars" class="space-y-6">
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700">Search Query</label>
                    <input v-model="query" type="text" id="query"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="e.g., Diesel SUV in Addis Ababa">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <select v-model="location" id="location"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Locations</option>
                        <option value="Addis Ababa">Addis Ababa</option>
                        <option value="Dire Dawa">Dire Dawa</option>
                        <option value="Hawassa">Hawassa</option>
                        <option value="Mekelle">Mekelle</option>
                        <option value="Bahir Dar">Bahir Dar</option>
                    </select>
                </div>

                <div>
                    <label for="fuelType" class="block text-sm font-medium text-gray-700">Fuel Type</label>
                    <select v-model="fuelType" id="fuelType"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All</option>
                        <option value="electric">Electric</option>
                        <option value="gas">Gas</option>
                        <option value="diesel">Diesel</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-green-600 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Search
                </button>
            </form>

            <div v-if="cars.length" class="mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Results: {{ cars.length }} found</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="car in cars" :key="car.id"
                        class="bg-white p-4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ car.name }}</h3>
                        <p class="text-sm text-gray-600">Location: {{ car.location }}</p>
                        <p class="text-sm text-gray-600">Fuel Type: {{ car.fuel_type }}</p>
                        <!-- format price in , and . -->
                        <p class="text-sm font-semibold text-gray-800">Price: {{ new Intl.NumberFormat('en-ET', {
                            style:
                                'currency', currency: 'ETB'
                        }).format(car.price) }}</p>
                        <!-- <p class="text-sm font-semibold text-gray-800">Price: {{ car.price }} ETB</p> -->
                        <!-- <p class="text-sm text-gray-500 mt-2">{{ car.description.substring(0, 250) }}...</p> -->
                        <p class="text-sm text-gray-500 mt-2">{{ car.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            query: '',
            location: '',
            fuelType: '',
            cars: [],
        };
    },
    methods: {
        async searchCars() {
            try {
                if (!this.query) {
                    console.error('Please enter a search query');
                    return;
                }
                const params = {
                    query: this.query,
                    location: this.location,
                    fuel_type: this.fuelType,
                };
                const response = await axios.get('/api/cars/search', { params });
                this.cars = response.data.cars;
            } catch (error) {
                console.error('Error searching cars:', error.response.data);
            }
        },
    },
};
</script>
