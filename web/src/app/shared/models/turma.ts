import { ClassItem } from "./class";
import { SchoolYearItem } from "./schoolYear";

export interface ITurma{
  turmas:  TurmaItem[],
  classes ? :  any
}

export interface ITurmaView {
  turmas: TurmaItem[];
  classes: ClassItem[];
  school_year: SchoolYearItem[];
  company_id? : string;
   [key: string]: any;
}


export interface TurmaItem {
  id?: number,
  designation: string,
  description: string,
  status?: number,
  token?: string
}
