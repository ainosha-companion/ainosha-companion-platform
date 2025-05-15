import { Agent } from "@/core/agent"
import Image from "next/image"
import Link from "next/link"

const Info = ({ profile }: { profile: Agent }) => {
    return (
        <div className="col-span-2 overflow-hidden h-full rounded-lg shadow-lg relative ">
            <div className="p-4 flex flex-col justify-between items-center text-center h-full">
                <div className="flex flex-col items-center gap-4">
                    <div className="flex-shrink-0">
                        <Image
                            src={profile.avatarURL}
                            alt={profile.name}
                            width={180}
                            height={180}
                            className="rounded-full w-32 h-32 md:w-44 md:h-44 lg:w-48 lg:h-48 object-cover"
                        />
                    </div>
                </div>
                <h2 className="mt-2 text-xl md:text-2xl lg:text-3xl font-extrabold text-white text-center truncate uppercase">
                    {profile.name}
                </h2>

                <div className="mt-4 px-4 md:px-8 lg:px-20">
                    <p className=" text-sm md:text-lg  text-gray-400 ">
                        {profile.description}
                    </p>
                </div>

                <div className="mt-4 mb-4">
                    <Link
                        href={`/agents/${profile.slug}`}
                        className="px-4 md:px-6 lg:px-8 h-8 md:h-10 lg:h-12 rounded-full 
                        font-bold tracking-wider 
                        border border-primary 
                        text-primary 
                        transition-all duration-300 
                        focus:outline-none 
                        active:scale-95
                        disabled:opacity-70 disabled:cursor-not-allowed
                        hover:bg-primary/80 hover:text-white hover:shadow-lg
                        inline-flex items-center justify-center text-sm md:text-base lg:text-lg"
                    >
                        Go to {profile.name}
                    </Link>
                </div>
            </div>
        </div>
    )
}

export default Info
