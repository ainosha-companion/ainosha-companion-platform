import Link from "next/link"
import IconArrowBackward from "./icon/icon-arrow-backward"

interface BackComponentProps {
    link?: string
    title: string
    className?: string
}
const BackComponent = ({ link, title, className }: BackComponentProps) => {
    return (
        <Link
            href={link || "/"}
            className={`inline-flex py-4 px-4 flex-row items-center gap-2 hover:text-white text-sm text-gray-400 transition duration-300 ${className}`}
        >
            <IconArrowBackward />
            <span className="">Back to {title}</span>
        </Link>
    )
}

export default BackComponent
