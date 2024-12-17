import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-declaration',
  templateUrl: './declaration.component.html',
  styleUrls: ['./declaration.component.css']
})
export class DeclarationComponent implements OnInit {
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
 base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Declaração C/S Nota',  url: '/dash/pedagogical-area/declaration' }]
  ngOnInit(): void {
  }

}
