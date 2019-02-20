let doCache = true; // Set this to true for production
let CACHE_NAME = "my-pwa-cache-v1";

self.addEventListener("activate", event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(keyList =>
            Promise.all(
                keyList.map(key => {
                    if (!cacheWhitelist.includes(key)) {
                        console.log("Deleting cache: " + key);
                        return caches.delete(key);
                    }
                })
            )
        )
    );
});

self.addEventListener("install", function(event) {
    if (doCache) {
        event.waitUntil(
            caches.open(CACHE_NAME).then(function(cache) {
                fetch("manifest.json")
                    .then(response => {
                        response.json();
                    })
                    .then(assets => {
                        const urlsToCache = ["/",assets['main.js'],new Request("http://192.168.43.44:8000/storage/files/image/ee1c5ceabae938c6cbeaf3d9c58b2243.jpg",{mode:"no-cors"})];
                        cache.addAll(urlsToCache);
                        console.log("cached");
                    });
            })
        );
    }
});

// self.addEventListener("fetch", function(event) {
//     if (doCache) {
//         event.respondWith(
//             caches.match(event.request).then(function(response) {
//                 return response || fetch(event.request);
//             })
//         );
//     }
// });

self.addEventListener('fetch', function fetcher (event) {
    let request = event.request;
    // check if request
    if (request.url.indexOf('assets.contentful.com') > -1) {
        // contentful asset detected
        console.log("dedaaaaaaaans")
        event.respondWith(
            caches.match(event.request).then(function(response) {
                // return from cache, otherwise fetch from network
                return response || fetch(request);
            })
        );
    }
    // otherwise: ignore event
});