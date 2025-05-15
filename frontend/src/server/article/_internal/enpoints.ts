export class HTTPEndpoint {
    static GetArticlesAdmin = "admin/content/articles/owner"
    static GetPublishedArticles = (searchParams: string) => {
        return searchParams
            ? `public/articles?${searchParams}`
            : "public/articles"
    }
    static PublicArticleBySlug = (id: string) => {
        return `public/articles/${id}`
    }

    static GetOwnerArticleBySlug = (slug: string) => {
        return `admin/content/articles/${slug}`
    }

    static DeleteArticle = (slug: string) => {
        return `admin/content/articles/${slug}`
    }

    static UpdateArticle = (id: string) => {
        return `admin/content/articles/${id}`
    }

    static ArticleRequest = "admin/content/articles/request"
}
