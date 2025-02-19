<div class="text-center">
    @env('production')
    <p class="font-bold text-green-600">Live Mode · v1.0.0</p>
    @else
    <p class="font-bold text-red-600">Testing Mode · v1.0.0</p>
    @endenv
    <p><?= config('app.name') ?> &copy; 2025 · Developed by Micheal Ataklt <a href="tel:+251913833334" class="text-yellow-500">(0913833334)</a>.</p>
</div>