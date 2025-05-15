import Image from "next/image"
import React from "react"

interface DescriptionFeatureProps {
    image: string
    title: string
    description: string
    titleButton: string
    className?: string
}
const DescriptionFeature: React.FC<DescriptionFeatureProps> = (props) => {
    const { description, title, image, titleButton, className } = props
    return (
        <div
            className={`w-full  mx-auto px-4 py-6 bg-white rounded-md shadow-lg text-center border-[0.5px] border-primary/30 ${className ?? ""}`}
        >
            <div className="relative w-full h-40 sm:h-56 mb-6 rounded-xl overflow-hidden">
                <Image
                    src={image}
                    alt={title}
                    layout="fill"
                    className="object-contain object-center"
                />
            </div>
            <h2 className="text-lg sm:text-2xl font-bold text-white mb-3">
                {title}
            </h2>
            <p className="text-sm sm:text-base text-white mb-6">
                {description}
            </p>

            <button
                disabled
                className={`
                px-6 h-10 rounded-full 
                font-bold tracking-wider 
                border border-primary 
                text-primary 
                transition-all duration-300 
                focus:outline-none 
                active:scale-95
                disabled:opacity-70 disabled:cursor-not-allowed
                inline-flex items-center justify-center
            `}
            >
                {titleButton}
            </button>
        </div>
    )
}

export default DescriptionFeature
