import { ArticleItem } from "./article"

export interface ICategory {
  article_categories: CategoryItem[]
}

export interface CategoryItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string,
  article?: ArticleItem[]
}
