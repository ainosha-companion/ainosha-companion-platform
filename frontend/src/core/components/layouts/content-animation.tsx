"use client"
import React from "react"

const ContentAnimation = ({ children }: { children: React.ReactNode }) => {
    return (
        <>
            {/* BEGIN CONTENT AREA */}
            <div className={` animate__animated px-6 pb-6`}>{children}</div>
            {/* END CONTENT AREA */}
        </>
    )
}

export default ContentAnimation
