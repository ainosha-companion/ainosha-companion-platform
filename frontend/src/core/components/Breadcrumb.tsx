"use client"

import Link from "next/link"
import { usePathname } from "next/navigation"

const Breadcrumb = () => {
    const pathname = usePathname()
    const pathArray = pathname.split("/").filter((path) => path)

    // Xử lý trường hợp home là /agents
    if (pathname === "/" || pathname === "/agents") {
        pathArray.unshift("agents")
    }

    // Hàm viết hoa chữ cái đầu
    const capitalizeFirstLetter = (str: string) => {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
    }

    return (
        <div className="w-full px-6">
            <div className="p-2">
                <ul className="flex ">
                    {/* Home Icon */}
                    <li className="flex items-center flex-start">
                        <Link
                            href="/"
                            className="text-base font-medium hover:text-primary text-gray-600 dark:text-gray-400 flex items-center"
                        >
                            <svg
                                className="w-4 h-4 mr-2"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            <div className="hidden md:block">Home</div>
                        </Link>
                        {pathArray.length > 0 && (
                            <span className="px-3 text-gray-600">/</span>
                        )}
                    </li>

                    {/* Dynamic Breadcrumb */}
                    {pathArray.map((segment, index) => {
                        // Nếu là "agents", đặt lại href là "/"
                        const isAgentsHome = segment === "agents"
                        const href = isAgentsHome
                            ? "/"
                            : `/${pathArray.slice(0, index + 1).join("/")}`
                        const isLast = index === pathArray.length - 1

                        // Viết hoa chữ cái đầu tiên của mỗi từ
                        const formattedSegment = capitalizeFirstLetter(
                            segment.replace(/-/g, " "),
                        )

                        return (
                            <li key={href} className="flex items-center">
                                {isLast ? (
                                    <span className="text-base font-semibold text-dark dark:text-white max-w-[100px] sm:max-w-[200px] md:max-w-[300px] lg:max-w-[700px] truncate">
                                        {isAgentsHome
                                            ? "Agents"
                                            : `${formattedSegment}`}
                                    </span>
                                ) : (
                                    <Link
                                        href={href}
                                        className="text-base font-medium hover:text-primary text-gray-600 dark:text-gray-300"
                                    >
                                        {isAgentsHome
                                            ? "Agents"
                                            : formattedSegment}
                                    </Link>
                                )}
                                {!isLast && (
                                    <span className="px-3 text-gray-500 ">
                                        /
                                    </span>
                                )}
                            </li>
                        )
                    })}
                </ul>
            </div>
        </div>
    )
}

export default Breadcrumb
