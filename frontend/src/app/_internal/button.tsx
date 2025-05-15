import React from "react"
import Loading from "./loading"
import { cn } from "./utils/style"

interface Props extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    isLoading?: boolean
    className?: string
    children: React.ReactNode
}

export default function Button({
    isLoading = false,
    className,
    children,
    ...rest
}: Props) {
    return (
        <button
            disabled={isLoading}
            className={cn(
                "btn flex w-full items-center gap-2 border-0 bg-[linear-gradient(90deg,rgb(135,255,0),rgb(11,216,243))] py-3 shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)] transition-all duration-500 ease-in-out transform hover:scale-105 hover:brightness-110 font-bold",
                {
                    "cursor-wait bg-gray-300": isLoading,
                    "bg-blue-500 text-white hover:bg-blue-600": !isLoading,
                },
                className,
            )}
            {...rest}
        >
            {isLoading && <Loading />}
            {children}
        </button>
    )
}
