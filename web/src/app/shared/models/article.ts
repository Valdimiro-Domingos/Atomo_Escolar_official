import { CategoryItem } from "./category"
import { RetentionItem } from "./retention"
import { TaxItem } from "./tax"
import { TypeItem } from "./type"

export interface IArticle {
  articles: ArticleItem[]
}

export interface ArticleItem {
  id?: number,
  designation: string,
  description: string,
  article_type_id: number,
  article_category_id: number,
  retention_id: number,
  tax_id: number,
  price: number,
  status?: number,
  retention: RetentionItem,
  article_category: CategoryItem,
  tax: TaxItem,
  article_type: TypeItem,
  token?: string
}
