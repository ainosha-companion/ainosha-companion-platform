import Lottie from "lottie-react"

interface LoadingAnimationProps {
    className?: string
    isLoading?: boolean
}

const LoadingAnimation: React.FC<LoadingAnimationProps> = ({
    isLoading = true,
    className,
}) => {
    return (
        <Lottie
            animationData={require("../../../../public/animations/loading.json")}
            autoPlay
            loop
            className={`w-10 h-10 ${className} ${isLoading ? "block" : "hidden"}}`}
        />
    )
}

export default LoadingAnimation
