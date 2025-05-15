export const useSystem = () => {
    const innerWidth = typeof window !== "undefined" ? window.innerWidth : 0
    const isMobile = innerWidth < 768 ? true : false
    const isTablet = innerWidth >= 768 && innerWidth <= 1024 ? true : false
    const isDesktop = innerWidth > 1024 ? true : false
    return { innerWidth, isMobile, isTablet, isDesktop }
}
