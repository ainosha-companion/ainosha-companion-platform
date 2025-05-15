"use client"
import DOMPurify from "dompurify"

/**
 * Formatter utility to sanitize HTML content
 */
export const HtmlFormatter = {
    /**
     * Purifies HTML content to prevent XSS attacks
     *
     * @param html - Raw HTML content from the API
     * @returns Sanitized HTML content
     */
    purify: (html: string | undefined | null): string => {
        if (!html) return ""

        // Basic DOMPurify configuration
        const config = {
            ALLOWED_TAGS: [
                "h1",
                "h2",
                "h3",
                "h4",
                "h5",
                "h6",
                "p",
                "div",
                "span",
                "strong",
                "em",
                "u",
                "strike",
                "a",
                "img",
                "ul",
                "ol",
                "li",
                "blockquote",
                "pre",
                "code",
                "br",
                "hr",
                "table",
                "thead",
                "tbody",
                "tr",
                "th",
                "td",
                "figure",
                "figcaption",
            ],
            ALLOWED_ATTR: [
                "href",
                "src",
                "alt",
                "title",
                "class",
                "id",
                "style",
                "target",
                "rel",
            ],
            FORBID_CONTENTS: ["script", "style", "iframe", "object", "embed"],
            ADD_ATTR: ["target"],
            WHOLE_DOCUMENT: false,
        }

        // Return sanitized HTML
        return DOMPurify.sanitize(html, config)
    },
}

export default HtmlFormatter
