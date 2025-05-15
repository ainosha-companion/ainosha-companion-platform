export function formatRelativeTime(dateString: string): string {
    const updated = new Date(dateString)
    const now = new Date()
    const diffInMs = now.getTime() - updated.getTime()
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60))
    const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60))
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24))

    if (diffInMinutes < 60) {
        return `${diffInMinutes || 1} minute${diffInMinutes === 1 ? "" : "s"} ago`
    } else if (diffInHours < 24) {
        return `${diffInHours} hour${diffInHours === 1 ? "" : "s"} ago`
    } else if (diffInDays < 7) {
        return `${diffInDays} day${diffInDays === 1 ? "" : "s"} ago`
    } else {
        return updated.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            year: "numeric",
        })
    }
}
