import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.css']
})
export class ReportsComponent implements OnInit {
  filter =  [
    {
      label: 'Nome',
      select: [
        'pedro',
        'lucas',
        'fabio'
      ]
    }
  ]
  constructor() { }

base = [{ name: 'Definições',  url: '/definitions' }, { name: 'Gestão de Utilizadores',  url: '/dash/definitions/users' }, { name: 'Utilizador',  url: '/dash/definitions/users' }]
  ngOnInit(): void {
  }

}
