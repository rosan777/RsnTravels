<script>
  document.addEventListener('DOMContentLoaded', function () {
    // status words in tables become coloured badges
    var colors = { Pending: 'warning', Confirmed: 'info', Cancelled: 'danger', Rejected: 'danger', CheckedIn: 'primary', Completed: 'success', PAID: 'success', UNPAID: 'danger', Available: 'success', Occupied: 'danger', Cleaning: 'warning', Maintenance: 'secondary' }
    document.querySelectorAll('td').forEach(function (td) {
      var text = td.textContent.trim()
      if (td.children.length === 0 && colors[text]) {
        td.innerHTML = '<span class="badge badge-status badge-' + colors[text] + '">' + text + '</span>'
      }
    })
    document.querySelectorAll('span.badge:not(.badge-status)').forEach(function (b) {
      var text = b.textContent.trim()
      if (colors[text]) b.className = 'badge badge-status badge-' + colors[text]
    })

    // fade-up when a block scrolls into view
    var items = document.querySelectorAll('.reveal')
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target) } })
      }, { threshold: 0.12 })
      items.forEach(function (el, i) { el.style.transitionDelay = (i % 4) * 80 + 'ms'; io.observe(el) })
    } else {
      items.forEach(function (el) { el.classList.add('in') })
    }

    // count up numbers
    document.querySelectorAll('[data-count]').forEach(function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10), suffix = el.getAttribute('data-suffix') || ''
      var started = false
      new IntersectionObserver(function (entries, obs) {
        if (entries[0].isIntersecting && !started) {
          started = true
          var n = 0, step = Math.max(1, Math.round(target / 40))
          var timer = setInterval(function () { n = Math.min(target, n + step); el.textContent = n.toLocaleString() + suffix; if (n >= target) clearInterval(timer) }, 30)
        }
      }).observe(el)
    })
  })
</script>
