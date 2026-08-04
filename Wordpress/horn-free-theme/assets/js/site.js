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
  var emailDialog = document.getElementById("email-dialog");
  var emailDialogStatus = document.getElementById("email-dialog-status");
  var supporterId = 0;
  var confirmationToken = "";
  var emailDraft = null;
  var dialogTrigger = null;

  var SITE_URL = window.hfiSettings && hfiSettings.siteUrl ? hfiSettings.siteUrl : window.location.origin && window.location.origin.indexOf("http") === 0
    ? window.location.href.split("#")[0]
    : "https://www.hornfreeindia.org/";

  function buildEmailDraft(name, state, country) {
    var to = emailBtn && emailBtn.dataset.to ? emailBtn.dataset.to : "nitin.gadkari@nic.in";
    var cc = emailBtn && emailBtn.dataset.cc ? emailBtn.dataset.cc : "shanti@hornfreeindia.org";
    var subject = "Request to Replace 'Blow Horn' Signage on Commercial Vehicles";
    var body =
      "Respected Shri Gadkari ji,\n\n" +
      "I am " + name + ", a citizen from " + state + ", " + country + ", writing to respectfully request " +
      "that the Ministry of Road Transport & Highways consider replacing the 'Blow Horn' and 'Horn Please' " +
      "signage on commercial vehicles with safer, calmer alternatives such as 'Stop Horn', 'No Horn', or 'Om Shanti'.\n\n" +
      "In 2015, Maharashtra showed this can be done through a single administrative circular, with no new law " +
      "required. Such a change would reduce noise pollution, protect public health, and reflect the values of " +
      "peace that India is known for.\n\n" +
      "I add my voice to thousands of citizens of Horn Free India who hope for quieter, safer streets.\n\n" +
      "With respect and gratitude,\n" + name + "\n" + state + ", " + country;
    return { to: to, cc: cc, subject: subject, body: body };
  }

  function emailUrl(provider) {
    if (!emailDraft) return "";
    var d = emailDraft;
    if (provider === "gmail") return "https://mail.google.com/mail/?view=cm&fs=1&to=" + encodeURIComponent(d.to) + "&cc=" + encodeURIComponent(d.cc) + "&su=" + encodeURIComponent(d.subject) + "&body=" + encodeURIComponent(d.body);
    if (provider === "outlook") return "https://outlook.live.com/mail/0/deeplink/compose?to=" + encodeURIComponent(d.to) + "&cc=" + encodeURIComponent(d.cc) + "&subject=" + encodeURIComponent(d.subject) + "&body=" + encodeURIComponent(d.body);
    return "mailto:" + d.to + "?cc=" + encodeURIComponent(d.cc) + "&subject=" + encodeURIComponent(d.subject) + "&body=" + encodeURIComponent(d.body);
  }

  function copyableEmail() {
    if (!emailDraft) return "";
    return "To: " + emailDraft.to + "\nCC: " + emailDraft.cc + "\nSubject: " + emailDraft.subject + "\n\n" + emailDraft.body;
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

  function activateEmailStep(name, state, country) {
    emailDraft = buildEmailDraft(name, state, country);
    if (emailBtn) emailBtn.setAttribute("href", emailUrl("default"));
    if (shareBtn) shareBtn.setAttribute("href", buildWhatsapp(name));
    unlock(step2);
  }

  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      var name = form.name.value.trim();
      var state = form.state.value.trim();
      var countryCode = form.country.value;
      var country = form.country.options[form.country.selectedIndex].text;
      var email = form.email.value.trim();

      if (!name || !state || !countryCode || !email) {
        showError("Please fill in every field so we can count you correctly.");
        return;
      }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError("That email doesn't look right — please check it.");
        return;
      }
      hideError();

      var submit = form.querySelector('[type="submit"]');
      if (submit) submit.disabled = true;
      try {
        var payload = new URLSearchParams({ action: "hfi_join_movement", nonce: hfiSettings.nonce, name: name, state: state, country: countryCode, email: email });
        var response = await fetch(hfiSettings.ajaxUrl, { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: payload.toString() });
        var result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.data && result.data.message ? result.data.message : hfiSettings.genericError);
        supporterId = Number(result.data.supporterId) || 0;
        confirmationToken = result.data.token || "";
        if (result.data.confirmed) unlock(step3);
        hideError();
      } catch (err) {
        showError(err.message || hfiSettings.genericError);
        if (submit) submit.disabled = false;
        return;
      }

      activateEmailStep(name, state, country);

      // Confirm + guide the user to step 2.
      var head = form.closest(".step").querySelector(".step-copy");
      if (head) head.textContent = "Thank you, " + name + ". Click Send my email below to add your voice to the public count.";
      if (step2) step2.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "center" });
      if (submit) submit.disabled = false;
    });
  }

  function openEmailDialog(trigger) {
    if (!emailDialog || !emailDraft) return;
    dialogTrigger = trigger;
    emailDialog.hidden = false;
    document.body.classList.add("email-dialog-open");
    if (emailDialogStatus) emailDialogStatus.textContent = "";
    var first = emailDialog.querySelector("[data-email-method]");
    if (first) first.focus();
  }

  function closeEmailDialog() {
    if (!emailDialog) return;
    emailDialog.hidden = true;
    document.body.classList.remove("email-dialog-open");
    if (dialogTrigger) dialogTrigger.focus();
  }

  async function recordEmailChoice() {
    if (!supporterId || !confirmationToken) return;
    var payload = new URLSearchParams({ action: "hfi_confirm_email_click", nonce: hfiSettings.nonce, supporterId: String(supporterId), token: confirmationToken });
    var response = await fetch(hfiSettings.ajaxUrl, { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: payload.toString(), keepalive: true });
    var result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.data && result.data.message ? result.data.message : hfiSettings.genericError);
    counters.forEach(function (el) { el.setAttribute("data-target", String(result.data.count)); el.textContent = Number(result.data.count).toLocaleString("en-IN"); });
    confirmationToken = "";
    unlock(step3);
    try { localStorage.setItem(STORAGE_KEY, "1"); } catch (err) {}
  }

  async function copyEmailMessage() {
    var text = copyableEmail();
    if (navigator.clipboard && window.isSecureContext) {
      await navigator.clipboard.writeText(text);
      return;
    }
    var area = document.createElement("textarea");
    area.value = text;
    area.setAttribute("readonly", "");
    area.style.position = "fixed";
    area.style.opacity = "0";
    document.body.appendChild(area);
    area.select();
    document.execCommand("copy");
    area.remove();
  }

  if (emailBtn) {
    emailBtn.addEventListener("click", function (e) {
      if (!emailDraft || emailBtn.getAttribute("href") === "#") return;
      e.preventDefault();
      openEmailDialog(emailBtn);
    });
  }

  if (emailDialog) {
    emailDialog.addEventListener("click", async function (e) {
      var close = e.target.closest("[data-email-close]");
      if (close) { closeEmailDialog(); return; }
      var button = e.target.closest("[data-email-method]");
      if (!button) return;
      var method = button.getAttribute("data-email-method");
      var popup = (method === "gmail" || method === "outlook") ? window.open("about:blank", "_blank") : null;
      button.disabled = true;
      emailDialog.setAttribute("aria-busy", "true");
      if (emailDialogStatus) emailDialogStatus.textContent = "Preparing your message…";
      try {
        await recordEmailChoice();
        if (method === "copy") {
          await copyEmailMessage();
          if (emailDialogStatus) emailDialogStatus.textContent = "Email message copied. Paste it into your preferred email service.";
        } else if (method === "default") {
          closeEmailDialog();
          window.location.href = emailUrl("default");
        } else if (popup) {
          popup.opener = null;
          popup.location.href = emailUrl(method);
          closeEmailDialog();
        } else {
          window.open(emailUrl(method), "_blank", "noopener");
          closeEmailDialog();
        }
      } catch (err) {
        if (popup) popup.close();
        if (emailDialogStatus) emailDialogStatus.textContent = err.message || hfiSettings.genericError;
      } finally {
        button.disabled = false;
        emailDialog.removeAttribute("aria-busy");
      }
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !emailDialog.hidden) closeEmailDialog();
    });
  }

  function showError(msg) { if (errorEl) { errorEl.textContent = msg; errorEl.hidden = false; } }
  function hideError() { if (errorEl) { errorEl.hidden = true; } }

  /* ---------- Rotating voices (placeholder set) ---------- */
  var voices = [
    { q: "I've lived on the GT Road for 20 years. I forgot what quiet sounds like. I want it back.", a: "— A supporter" },
    { q: "My daughter covers her ears every morning on the way to school. That's not normal. We can fix it.", a: "— A parent" },
    { q: "I drive a truck. I never wanted my work to be the loudest thing on the street.", a: "— A driver" }
  ];
  var voicesEl = document.querySelector(".voices[data-voices]");
  if (voicesEl) {
    try { voices = JSON.parse(voicesEl.getAttribute("data-voices")); } catch (err) {}
  }
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
