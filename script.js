(function () {
  const inputs = Array.from(document.querySelectorAll("input.autocomplete"));
  if (inputs.length === 0) return;

  const timeouts = new WeakMap();

  function clearAllSuggestions() {
    document
      .querySelectorAll(".suggestions")
      .forEach((el) => (el.innerHTML = ""));
  }

  document.addEventListener("click", (e) => {
    if (
      !e.target.closest(".suggestions") &&
      !e.target.classList.contains("autocomplete")
    ) {
      clearAllSuggestions();
    }
  });

  inputs.forEach((input) => {
    const form = input.closest("form");
    if (!form) return;
    let suggestionsEl = form.querySelector(".suggestions");
    if (!suggestionsEl) {
      suggestionsEl = document.createElement("div");
      suggestionsEl.className = "suggestions";
      suggestionsEl.setAttribute("aria-live", "polite");
      form.appendChild(suggestionsEl);
    }

    input.addEventListener("input", () => {
      const q = input.value.trim();
      const prev = timeouts.get(input);
      if (prev) clearTimeout(prev);
      if (q === "") {
        suggestionsEl.innerHTML = "";
        return;
      }
      const id = setTimeout(
        () => fetchSuggestions(q, suggestionsEl, input),
        200
      );
      timeouts.set(input, id);
    });
  });

  function fetchSuggestions(q, suggestionsEl, input) {
    fetch("recherche.php?ajax=1&search=" + encodeURIComponent(q))
      .then((r) => r.json())
      .then((data) => renderSuggestions(data, suggestionsEl, input))
      .catch((err) => {
        console.error(err);
        suggestionsEl.innerHTML = "";
      });
  }

  function renderSuggestions(data, suggestionsEl, input) {
    const { exact = [], contains = [] } = data || {};
    if (exact.length === 0 && contains.length === 0) {
      suggestionsEl.innerHTML = "";
      return;
    }

    let html = "";
    if (exact.length > 0) {
      html += '<div class="group"><h4>Commence par</h4>';
      exact.forEach(
        (it) =>
          (html += `<div class="item" data-id="${it.id}">${escapeHtml(
            it.name
          )}</div>`)
      );
      html += "</div>";
    }
    if (contains.length > 0) {
      html += '<div class="group"><h4>Contient</h4>';
      contains.forEach(
        (it) =>
          (html += `<div class="item" data-id="${it.id}">${escapeHtml(
            it.name
          )}</div>`)
      );
      html += "</div>";
    }
    suggestionsEl.innerHTML = html;

    suggestionsEl.querySelectorAll(".item").forEach((el) => {
      el.addEventListener("click", () => {
        const id = el.getAttribute("data-id");
        if (id)
          window.location.href = "element.php?id=" + encodeURIComponent(id);
      });
    });
  }

  function escapeHtml(s) {
    return String(s).replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        }[c])
    );
  }
})();
