import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-classification',
  templateUrl: './classification.component.html',
  styleUrls: ['./classification.component.css']
})
export class ClassificationComponent implements OnInit {
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
   base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Classificação',  url: '/dash/pedagogical-area/classification' }]

  ngOnInit(): void {
  }

}
