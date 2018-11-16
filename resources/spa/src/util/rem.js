((doc, win) => {
  const docEl = doc.documentElement
  const resizeEvt =
    'orientationchange' in window ? 'orientationchange' : 'resize'
  const recalc = () => {
    let clientWidth = docEl.clientWidth
    if (!clientWidth) return
    clientWidth = clientWidth > 780 ? 780 : clientWidth
    const rem = 100 * (clientWidth / 750)
    win.$REM = rem
    docEl.style.fontSize = `${rem}px`
  }
  if (!doc.addEventListener) return
  win.addEventListener(resizeEvt, recalc, false)
  doc.addEventListener('DOMContentLoaded', recalc, false)
})(document, window)
