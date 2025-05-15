interface ArticleInfoProps {
    title: string
    category: string[]
    author: string
    updated_at: string
}

const ArticleInfo: React.FC<ArticleInfoProps> = (props) => {
    const { title, category, author, updated_at } = props
    return (
        <div id="article-info" className=" text-white my-3 px-4 rounded-lg">
            <div className="text-gray-400 text-sm mb-2">{category}</div>

            <h1 className="text-4xl font-bold mb-8">{title}</h1>

            {category.length > 0 && (
                <div className="flex space-x-2 mb-4">
                    <div className="flex items-center bg-gray-800 rounded-full px-4 py-1">
                        <span className="text-blue-400">{category}</span>
                    </div>
                </div>
            )}

            <div className="flex items-center mt-6">
                <div className="w-12 h-12 rounded-full bg-blue-400 flex items-center justify-center mr-4">
                    <div className="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-800" />
                </div>
                <div>
                    <div className="flex items-center">
                        <span className="text-gray-400 mr-1">By</span>
                        <span className="font-bold">{author}</span>
                    </div>
                    <div className="text-gray-400 text-sm">
                        {updated_at} • 4 mins read
                    </div>
                </div>
            </div>
        </div>
    )
}
export default ArticleInfo
