import clsx from "clsx"
import Image from "next/image"

type ChatItemProps = {
    id: string
    name: string
    logo: string
    isActive: boolean
    isHover?: boolean
    onSelect?: (id: string) => void
}
export default function TokenItem({
    id,
    name,
    logo,
    isActive,
    onSelect,
}: ChatItemProps) {
    return (
        <li
            onClick={() => onSelect && onSelect(id)}
            className={clsx(
                "flex w-full cursor-pointer items-center justify-between rounded-md p-2",
                {
                    "bg-gray-100 text-primary dark:bg-[#050b146a] dark:text-primary":
                        isActive,
                    "hover:bg-gray-100 hover:text-primary dark:hover:bg-[#050b146a] dark:hover:text-primary":
                        !isActive,
                },
            )}
        >
            <div className="flex-1">
                <div className="flex items-center">
                    <div className="relative flex-shrink-0">
                        <Image
                            src={logo}
                            alt=""
                            className=" rounded-full object-cover w-8 h-8"
                            width={32}
                            height={32}
                        />
                        <div className="absolute bottom-0 right-0 h-3 w-3 rounded-full ">
                            <div className="h-3 w-3 rounded-full bg-success" />
                        </div>
                    </div>
                    <div className="mx-3 ltr:text-left rtl:text-right">
                        <p className="mb-1 font-semibold">{name}</p>
                        {/* <p className="max-w-[185px] truncate text-xs text-white-dark">{chat.lastMessage}</p> */}
                    </div>
                </div>
            </div>
        </li>
    )
}
