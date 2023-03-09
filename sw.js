const cacheName = "meg-chat";
const filesToCache = [
    "/resources/icons/android-icon-36x36.pn",
    "/resources/icons/android-icon-48x48.png",
    "/resources/icons/android-icon-72x72.png",
    "/resources/icons/android-icon-96x96.png",
    "/resources/icons/android-icon-144x144.png",
    "/resources/icons/android-icon-192x192.png",
    "/resources/icons/resources/images/avatar.png"
];

self.addEventListener("install", e => {
  console.log("[ServiceWorker**] Install");
  e.waitUntil(
    caches.open(cacheName).then(cache => {
      console.log("[ServiceWorker**] Caching app shell");
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.filter(function(tcacheName) {
			return tcacheName != cacheName;
        }).map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request, { ignoreSearch: true }).then(response => {
      return fetch(event.request) || response;
    })
  );
});
