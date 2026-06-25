/* ============================================================
   Horn Free India — homepage behaviour
   No framework, no build step. Plain ES module-free JS.
   ============================================================ */
(function () {
  "use strict";

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var STORAGE_KEY = "hfi_joined";

  /* ---------- Mobile nav ---------- */
  var toggle = document.querySelector(".nav-toggle");
  var menu = document.getElementById("nav-menu");
  if (toggle && menu) {
    toggle.addEventListener("click", function () {
      var open = menu.classList.toggle("open");
      toggle.setAttribute("aria-expanded", String(open));
    });
    menu.addEventListener("click", function (e) {
      if (e.target.tagName === "A") {
        menu.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* ---------- Animated count-up ---------- */
  function animateCounter(el) {
    var target = parseInt(el.getAttribute("data-target"), 10) || 0;
    if (reduceMotion) { el.textContent = target.toLocaleString("en-IN"); return; }
    var start = performance.now();
    var dur = 1500;
    function tick(now) {
      var p = Math.min((now - start) / dur, 1);
      // easeOutCubic
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.floor(eased * target).toLocaleString("en-IN");
      if (p < 1) requestAnimationFrame(tick);
      else el.textContent = target.toLocaleString("en-IN");
    }
    requestAnimationFrame(tick);
  }

  var counters = Array.prototype.slice.call(document.querySelectorAll("[data-counter]"));
  var counterStarted = new WeakSet();
  if ("IntersectionObserver" in window) {
    var cObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting && !counterStarted.has(entry.target)) {
          counterStarted.add(entry.target);
          animateCounter(entry.target);
          cObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    counters.forEach(function (c) { cObs.observe(c); });
  } else {
    counters.forEach(animateCounter);
  }

  // Bump every counter's displayed target by 1 (used after someone joins)
  function incrementCounters() {
    counters.forEach(function (el) {
      var t = parseInt(el.getAttribute("data-target"), 10) || 0;
      t += 1;
      el.setAttribute("data-target", String(t));
      el.textContent = t.toLocaleString("en-IN");
    });
  }

  /* ---------- Reveal on scroll ---------- */
  var reveals = Array.prototype.slice.call(document.querySelectorAll(".reveal"));
  if (reduceMotion || !("IntersectionObserver" in window)) {
    reveals.forEach(function (r) { r.classList.add("in"); });
  } else {
    var rObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add("in"); rObs.unobserve(entry.target); }
      });
    }, { threshold: 0.15 });
    reveals.forEach(function (r) { rObs.observe(r); });
  }

  /* ---------- Take Action engine ---------- */
  var form = document.getElementById("join-form");
  var errorEl = document.getElementById("form-error");
  var step2 = document.getElementById("step-2");
  var step3 = document.getElementById("step-3");
  var emailBtn = document.getElementById("email-btn");
  var shareBtn = document.getElementById("share-btn");

  var SITE_URL = window.location.origin && window.location.origin.indexOf("http") === 0
    ? window.location.href.split("#")[0]
    : "https://www.hornfreeindia.org/";

  function buildMailto(name, city, state) {
    var to = "thakorrahul919@gmail.com";
    var cc = "shanti@hornfreeindia.org";
    var subject = "Request to Replace 'Blow Horn' Signage on Commercial Vehicles";
    var body =
      "Respected Shri Gadkari ji,\n\n" +
      "I am " + name + ", a citizen from " + city + ", " + state + ", writing to respectfully request " +
      "that the Ministry of Road Transport & Highways consider replacing the 'Blow Horn' and 'Horn Please' " +
      "signage on commercial vehicles with safer, calmer alternatives such as 'Stop Horn', 'No Horn', or 'Om Shanti'.\n\n" +
      "In 2015, Maharashtra showed this can be done through a single administrative circular, with no new law " +
      "required. Such a change would reduce noise pollution, protect public health, and reflect the values of " +
      "peace that India is known for.\n\n" +
      "I add my voice to thousands of citizens of Horn Free India who hope for quieter, safer streets.\n\n" +
      "With respect and gratitude,\n" + name + "\n" + city + ", " + state;
    return "mailto:" + to +
      "?cc=" + encodeURIComponent(cc) +
      "&subject=" + encodeURIComponent(subject) +
      "&body=" + encodeURIComponent(body);
  }

  function buildWhatsapp(name) {
    var text = "I just joined Horn Free India — a citizen movement to replace 'Blow Horn' signage on our trucks " +
      "with messages of peace, for quieter and safer streets. It costs nothing. Add your voice: " + SITE_URL;
    return "https://wa.me/?text=" + encodeURIComponent(text);
  }

  function unlock(step) {
    if (!step) return;
    step.classList.remove("step-locked");
    step.classList.add("step-unlocked");
    step.setAttribute("aria-hidden", "false");
  }

  function activateNextSteps(name, city, state) {
    if (emailBtn) emailBtn.setAttribute("href", buildMailto(name, city, state));
    if (shareBtn) shareBtn.setAttribute("href", buildWhatsapp(name));
    unlock(step2);
    unlock(step3);
  }

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var name = form.name.value.trim();
      var city = form.city.value.trim();
      var state = form.state.value.trim();
      var email = form.email.value.trim();

      if (!name || !city || !state || !email) {
        showError("Please fill in every field so we can count you correctly.");
        return;
      }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError("That email doesn't look right — please check it.");
        return;
      }
      hideError();

      // NOTE: front-end only for now. When the backend exists, POST these
      // fields here so the count is real and server-side.
      incrementCounters();
      try { localStorage.setItem(STORAGE_KEY, "1"); } catch (err) {}

      activateNextSteps(name, city, state);

      // Confirm + guide the user to step 2.
      var head = form.closest(".step").querySelector(".step-copy");
      if (head) head.textContent = "Thank you, " + name + " — you're counted. Now make it land: send your email below.";
      if (step2) step2.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "center" });
    });
  }

  function showError(msg) { if (errorEl) { errorEl.textContent = msg; errorEl.hidden = false; } }
  function hideError() { if (errorEl) { errorEl.hidden = true; } }

  /* ---------- Rotating voices (placeholder set) ---------- */
  var voices = [
    { q: "I've lived on the GT Road for 20 years. I forgot what quiet sounds like. I want it back.", a: "— [Name, City]" },
    { q: "My daughter covers her ears every morning on the way to school. That's not normal. We can fix it.", a: "— [Name, City]" },
    { q: "I drive a truck. I never wanted my work to be the loudest thing on the street.", a: "— [Name, City]" }
  ];
  var qEl = document.getElementById("voice-quote");
  var aEl = document.getElementById("voice-author");
  if (qEl && aEl && !reduceMotion) {
    var vi = 0;
    setInterval(function () {
      vi = (vi + 1) % voices.length;
      qEl.style.opacity = 0;
      aEl.style.opacity = 0;
      setTimeout(function () {
        qEl.textContent = '"' + voices[vi].q + '"';
        aEl.textContent = voices[vi].a;
        qEl.style.opacity = 1;
        aEl.style.opacity = 1;
      }, 350);
    }, 5500);
    qEl.style.transition = aEl.style.transition = "opacity .35s ease";
  }
})();
