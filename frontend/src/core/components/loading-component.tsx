import LoadingAnimation from "./common/loadingAnimation"

const LoadingComponent = ({ className }: { className?: string }) => {
    return (
        <div
            className={`flex  w-full items-center justify-center ${className}`}
        >
            <LoadingAnimation className="w-20 h-20" />
        </div>
    )
}

export default LoadingComponent
