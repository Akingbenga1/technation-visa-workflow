<div class="container mx-auto">
    <div class="bg-gradient-to-r from-purple-400 to-pink-500 p-4">
        <h1 class="text-2xl font-bold text-white">Header</h1>
    </div>
    <div class="mt-4">
        <div class="carousel">
            <div class="carousel-item active">
                <img src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg" alt="Sunset over a solitary tree in a field" class="w-full h-96 object-cover">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.pixabay.com/photo/2016/11/29/09/16/architecture-1868667_1280.jpg" alt="Modern architectural building with glass facade" class="w-full h-96 object-cover">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.pixabay.com/photo/2017/02/20/18/03/cat-2083492_1280.jpg" alt="Close-up of a cat with striking green eyes" class="w-full h-96 object-cover">
            </div>
        </div>
    </div>
    <div class="mt-4">
        <ul class="flex">
            <li class="mr-2">
                <a href="#section1" class="text-blue-500 hover:text-blue-700">Section 1</a>
            </li>
            <li class="mr-2">
                <a href="#section2" class="text-blue-500 hover:text-blue-700">Section 2</a>
            </li>
        </ul>
    </div>
    <div class="mt-4" id="section1">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-green-400 to-blue-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 1</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="bg-gradient-to-r from-green-400 to-blue-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 2</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="bg-gradient-to-r from-green-400 to-blue-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 3</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
    </div>
    <div class="mt-4" id="section2">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-yellow-400 to-red-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 4</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-400 to-red-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 5</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-400 to-red-500 p-4 shadow-md">
                <h2 class="text-lg font-bold text-white">Panel 6</h2>
                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
    </div>
</div>
<!-- 
<style>
    .carousel {
        position: relative;
        /* Ensure carousel has a defined height, e.g., matching image height, if not intrinsically sized by active item */
        /* Tailwind h-96 is 24rem (384px). If images define height, this might not be needed. */
    }
    .carousel-item {
        display: none; /* Hide items by default */
    }
    .carousel-item.active {
        display: block; /* Show active item */
    }
    .carousel-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 0.5rem 1rem; /* Equivalent to Tailwind's p-2 px-4 */
        cursor: pointer;
        z-index: 10; /* Ensure buttons are above images */
        font-size: 1rem;
        border-radius: 0.25rem; /* Equivalent to Tailwind's rounded */
    }
    .carousel-button.prev-button {
        left: 1rem; /* Equivalent to Tailwind's left-4 */
    }
    .carousel-button.next-button {
        right: 1rem; /* Equivalent to Tailwind's right-4 */
    }
</style> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('.carousel');
        if (!carousel) {
            console.warn('Carousel element not found.');
            return;
        }

        const items = Array.from(carousel.querySelectorAll('.carousel-item'));
        if (items.length === 0) {
            console.warn('No carousel items found.');
            return;
        }

        let current = 0;
        let autoplayIntervalId = null;

        // Determine initial active item
        const initialActiveItemIndex = items.findIndex(item => item.classList.contains('active'));
        if (initialActiveItemIndex !== -1) {
            current = initialActiveItemIndex;
        } else if (items.length > 0) {
            // If no item has 'active' class, make the first one active
            items[0].classList.add('active');
            current = 0;
        }


        function showItem(index) {
            items.forEach((item, idx) => {
                if (idx === index) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
            current = index;
        }

        function nextItem() {
            const newIndex = (current + 1) % items.length;
            showItem(newIndex);
        }

        function prevItem() {
            const newIndex = (current - 1 + items.length) % items.length;
            showItem(newIndex);
        }

        function startAutoplay() {
            if (items.length > 1) { // Only autoplay if there's more than one item
                clearInterval(autoplayIntervalId); // Clear existing interval if any
                autoplayIntervalId = setInterval(nextItem, 5000);
            }
        }

        function resetAutoplay() {
            if (items.length > 1) {
                clearInterval(autoplayIntervalId);
                autoplayIntervalId = setInterval(nextItem, 5000);
            }
        }
        
        // Initial display setup
        showItem(current);

        // Event listener for clicking on carousel items (optional behavior)
        carousel.addEventListener('click', function(event) {
            // Ignore clicks on buttons
            if (event.target.closest('.carousel-button')) {
                return;
            }

            const clickedItem = event.target.closest('.carousel-item');
            if (clickedItem && items.includes(clickedItem)) {
                const index = items.indexOf(clickedItem);
                if (index !== current) { // Only act if a different item is clicked
                    showItem(index);
                    resetAutoplay();
                }
            }
        });

        // Add navigation buttons
        const prevButton = document.createElement('button');
        prevButton.textContent = 'Prev';
        prevButton.classList.add('carousel-button', 'prev-button');
        prevButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent carousel click event
            prevItem();
            resetAutoplay();
        });
        carousel.insertBefore(prevButton, carousel.firstChild);

        const nextButton = document.createElement('button');
        nextButton.textContent = 'Next';
        nextButton.classList.add('carousel-button', 'next-button');
        nextButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent carousel click event
            nextItem();
            resetAutoplay();
        });
        carousel.appendChild(nextButton);

        // Start auto-play
        startAutoplay();
    });
</script>
