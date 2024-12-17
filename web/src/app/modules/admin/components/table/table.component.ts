import { Component, ContentChild, ElementRef, Input, OnInit, TemplateRef, ViewChild } from '@angular/core';
import * as feather from 'feather-icons'
import { Table } from 'primeng/table';
import { ExportAsConfig, ExportAsService } from 'ngx-export-as';
import { FilterPipe } from 'src/app/pipes/filter.pipe';



export interface TableItem {
  [key: string]: any;
}

@Component({
  selector: 'app-table',
  templateUrl: './table.component.html',
  styleUrls: ['./table.component.css']
})


export class TableComponent implements OnInit {
  @Input() heading = ["ID","Nome", "Genero", "Sala", "Classe", "Turno", "Estado", "Opções"]
  @Input() title = ''
  @Input() items: TableItem[] = [];
  @ContentChild(TemplateRef, { static: false }) renderItem!: TemplateRef<any>;
  searchTerm: string = '';
  @ViewChild('tabela', { static: false }) tabela!: ElementRef;
  @ViewChild('dt') dt!: Table;
  @ContentChild(TemplateRef, { static: false }) FilterPipes!: TemplateRef<any>;
  @ViewChild(FilterPipe) filterPipe!: FilterPipe;
  
  
  @Input() FielInput: boolean = false;
  
  
  exportAsConfig: ExportAsConfig = {
    type: 'pdf', // Pode ser 'pdf', 'xlsx' ou 'csv'
    elementIdOrContent: 'tabela',
  };
  
    filteredItems: any;
  
  
  
  constructor(private exportAsService: ExportAsService) {
  }
  
  customFilter() {
    this.filteredItems = this.filterPipe.transform(this.items, this.searchTerm);
  }
  
  
  ngOnInit(): void {
    this.filteredItems = this.items;
    feather.replace()
  }

  exportToPDF() {
    this.exportAsService.save(this.exportAsConfig, 'tabela').subscribe(() => {
      // Sucesso
    });
    this.exportAsService.get(this.exportAsConfig).subscribe(content => {
      (content);
    });
  }



}
