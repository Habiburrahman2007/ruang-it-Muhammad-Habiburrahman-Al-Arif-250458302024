import isDesktop from '../helper/isDesktop'

const calculateChildrenHeight = (el, deep = false) => {
  const children = el.children

  let height = 0
  for (let i = 0; i < el.childElementCount; i++) {
    const child = children[i]
    height += child.querySelector('.submenu-link').clientHeight

    if (deep && child.classList.contains('has-sub')) {
      const subsubmenu = child.querySelector('.submenu')

      if (subsubmenu.classList.contains('submenu-open')) {
        const childrenHeight = ~~[...subsubmenu.querySelectorAll('.submenu-link')].reduce((acc, curr) => acc + curr.clientHeight, 0)
        height += childrenHeight
      }
    }

  }
  el.style.setProperty('--submenu-height', height + 'px')
  return height
}

let globalEventDelegationSetup = false;

class Sidebar {
  constructor(el, options = {}) {
    this.sidebarEL = el instanceof HTMLElement ? el : document.querySelector(el)
    this.options = options
    this.init()
  }

  init() {
    console.log('Sidebar init() called');
    console.log('Sidebar element:', this.sidebarEL);
    console.log('Global delegation setup:', globalEventDelegationSetup);

    if (!globalEventDelegationSetup) {
      console.log('Setting up global event delegation for burger buttons');

      document.addEventListener('click', (e) => {
        const burgerBtn = e.target.closest('.burger-btn');
        const sidebarHide = e.target.closest('.sidebar-hide');

        if (burgerBtn) {
          console.log('Burger button clicked via delegation');
          e.preventDefault();
          const sidebar = document.getElementById('sidebar');
          if (sidebar && window.sidebarInstance) {
            window.sidebarInstance.toggle();
          }
        } else if (sidebarHide) {
          console.log('Sidebar hide clicked via delegation');
          e.preventDefault();
          const sidebar = document.getElementById('sidebar');
          if (sidebar && window.sidebarInstance) {
            window.sidebarInstance.toggle();
          }
        }
      });

      globalEventDelegationSetup = true;
    }

    window.addEventListener("resize", this.onResize.bind(this))


    const toggleSubmenu = (el) => {
      if (el.classList.contains("submenu-open")) {
        el.classList.remove('submenu-open')
        el.classList.add('submenu-closed')
      } else {
        el.classList.remove("submenu-closed")
        el.classList.add("submenu-open")
      }
    }

    let sidebarItems = document.querySelectorAll(".sidebar-item.has-sub")
    for (var i = 0; i < sidebarItems.length; i++) {
      let sidebarItem = sidebarItems[i]

      sidebarItems[i]
        .querySelector(".sidebar-link")
        .addEventListener("click", (e) => {
          e.preventDefault()
          let submenu = sidebarItem.querySelector(".submenu")
          toggleSubmenu(submenu)
        })


      const submenuItems = sidebarItem.querySelectorAll('.submenu-item.has-sub')
      submenuItems.forEach(item => {
        item.addEventListener('click', () => {
          const submenuLevelTwo = item.querySelector('.submenu')
          toggleSubmenu(submenuLevelTwo)

          const height = calculateChildrenHeight(item.parentElement, true)

        })
      })
    }

    if (typeof PerfectScrollbar == "function") {
      const container = document.querySelector(".sidebar-wrapper")
      const ps = new PerfectScrollbar(container, {
        wheelPropagation: true,
      })
    }

    setTimeout(() => {
      const activeSidebarItem = document.querySelector(".sidebar-item.active");
      if (activeSidebarItem) {
        this.forceElementVisibility(activeSidebarItem);
      }
    }, 300);


    if (this.options.recalculateHeight) {
      reInit_SubMenuHeight(sidebarEl)
    }

  }

  onResize() {
    if (isDesktop(window)) {
      this.sidebarEL.classList.add("active")
      this.sidebarEL.classList.remove("inactive")
    } else {
      this.sidebarEL.classList.remove("active")
    }

    this.deleteBackdrop()
    this.toggleOverflowBody(true)
  }

  toggle() {
    const sidebarState = this.sidebarEL.classList.contains("active")
    if (sidebarState) {
      this.hide()
    } else {
      this.show()
    }
  }

  show() {
    this.sidebarEL.classList.add("active")
    this.sidebarEL.classList.remove("inactive")
    this.createBackdrop()
    this.toggleOverflowBody()
  }

  hide() {
    this.sidebarEL.classList.remove("active")
    this.sidebarEL.classList.add("inactive")
    this.deleteBackdrop()
    this.toggleOverflowBody()
  }

  createBackdrop() {
    if (isDesktop(window)) return
    this.deleteBackdrop()
    const backdrop = document.createElement("div")
    backdrop.classList.add("sidebar-backdrop")
    backdrop.addEventListener("click", this.hide.bind(this))
    document.body.appendChild(backdrop)
  }

  deleteBackdrop() {
    const backdrop = document.querySelector(".sidebar-backdrop")
    if (backdrop) {
      backdrop.remove()
    }
  }

  toggleOverflowBody(active) {
    if (isDesktop(window)) return;
    const sidebarState = this.sidebarEL.classList.contains("active")
    const body = document.querySelector("body")
    if (typeof active == "undefined") {
      body.style.overflowY = sidebarState ? "hidden" : "auto"
    } else {
      body.style.overflowY = active ? "auto" : "hidden"
    }
  }

  isElementInViewport(el) {
    var rect = el.getBoundingClientRect()

    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    )
  }

  forceElementVisibility(el) {
    if (!this.isElementInViewport(el)) {
      el.scrollIntoView(false)
    }
  }
}



let sidebarEl = document.getElementById("sidebar")

const onFirstLoad = (sidebarEL) => {
  if (!sidebarEl) return
  if (isDesktop(window)) {
    sidebarEL.classList.add("active")
    sidebarEL.classList.add('sidebar-desktop')
  }

  let submenus = document.querySelectorAll(".sidebar-item.has-sub .submenu")
  for (var i = 0; i < submenus.length; i++) {
    let submenu = submenus[i]
    const sidebarItem = submenu.parentElement
    const height = submenu.clientHeight

    if (sidebarItem.classList.contains('active')) submenu.classList.add('submenu-open')
    else submenu.classList.add('submenu-closed')
    setTimeout(() => {
      const height = calculateChildrenHeight(submenu, true)
    }, 50);
  }
}

const reInit_SubMenuHeight = (sidebarEl) => {
  if (!sidebarEl) return

  let submenus = document.querySelectorAll(".sidebar-item.has-sub .submenu")
  for (var i = 0; i < submenus.length; i++) {
    let submenu = submenus[i]
    const sidebarItem = submenu.parentElement
    const height = submenu.clientHeight

    if (sidebarItem.classList.contains('active')) submenu.classList.add('submenu-open')
    else submenu.classList.add('submenu-closed')
    setTimeout(() => {
      const height = calculateChildrenHeight(submenu, true)
    }, 50);
  }
}


if (document.readyState !== 'loading') {
  onFirstLoad(sidebarEl)
}
else {
  window.addEventListener('DOMContentLoaded', () => onFirstLoad(sidebarEl))
}

window.Sidebar = Sidebar

const initializeSidebar = () => {
  console.log('initializeSidebar() called');
  sidebarEl = document.getElementById("sidebar")
  if (sidebarEl) {
    console.log('Sidebar element found, initializing...');
    onFirstLoad(sidebarEl)
    window.sidebarInstance = new window.Sidebar(sidebarEl)
    console.log('Sidebar instance created:', window.sidebarInstance);
  } else {
    console.log('Sidebar element NOT found!');
  }
}

initializeSidebar()

document.addEventListener('livewire:navigated', () => {
  initializeSidebar()
})