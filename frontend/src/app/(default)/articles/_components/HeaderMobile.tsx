"use client"
import { FaFilter } from "react-icons/fa"

type Props = {
    toggleFilter: () => void
}

export default function HeaderMobile({ toggleFilter }: Props) {
    return (
        <div className="sticky top-0 z-20 flex w-full items-center justify-between bg-gray-900 p-4 shadow-md md:hidden">
            <h2 className="text-xl font-bold">Articles</h2>
            <button
                className="flex items-center gap-2 rounded-lg bg-gray-800 px-3 py-2 text-sm text-white transition hover:bg-gray-700"
                onClick={toggleFilter}
            >
                <FaFilter size={14} />
                <span>Filter</span>
            </button>
        </div>
    )
}
