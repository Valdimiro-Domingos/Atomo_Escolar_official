import { Component, OnInit, ViewChild } from '@angular/core';
import { faCoffee } from '@fortawesome/free-solid-svg-icons';
import * as feather from 'feather-icons';
import { DashboardService } from '../../services/dashboard.service';
import { IDashboard } from 'src/app/shared/models/dashboard';
import { AuthService } from 'src/app/services/auth/auth.service';
import { NavBarHamburguerComponent } from '../../components/header/components/nav-bar-hamburguer/nav-bar-hamburguer.component';


@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  @ViewChild(NavBarHamburguerComponent) nav!: NavBarHamburguerComponent;
  faCoffee = faCoffee;
  isLoading: boolean = false
  basicData: any;
  basicOptions: any;
  datas: IDashboard = {
    dashboard: {
      enrollments: 0,
      users: 0,
      expenses: 0,
      revenue: 0,
      databases: {
        revenue: [],
        expenses: [],
        enrollments: []
      }
    }
  }
  constructor(private dashboardService: DashboardService, private authService: AuthService) {
    const token = this.authService.getUser().token

    this.dashboardService.getDatas(token).subscribe((data: any) => {
      const response = data as IDashboard

      if (data) {
        this.datas.dashboard = response.dashboard

        const documentStyle = getComputedStyle(document.documentElement);
        const textColor = documentStyle.getPropertyValue('--text-color');
        const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
        const surfaceBorder = documentStyle.getPropertyValue('--surface-border');


        this.data = {
          labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho'],
          datasets: [
            {
              type: 'line',
              label: 'MATRÌCULAS',
              borderColor: documentStyle.getPropertyValue('--blue-500'),
              borderWidth: 2,
              fill: false,
              tension: 0.4,
              data: this.datas.dashboard.databases.enrollments
            },
            {
              type: 'bar',
              label: 'RECEITAS',
              backgroundColor: documentStyle.getPropertyValue('--green-500'),
              data: this.datas.dashboard.databases.revenue,
              borderColor: 'white',
              borderWidth: 2
            },
            {
              type: 'bar',
              label: 'DESPESAS',
              backgroundColor: documentStyle.getPropertyValue('--orange-500'),
              data: this.datas.dashboard.databases.expenses
            }
          ]
        };

        this.options = {
          maintainAspectRatio: false,
          aspectRatio: 0.6,
          plugins: {
            legend: {
              labels: {
                color: textColor
              }
            }
          },
          scales: {
            x: {
              ticks: {
                color: textColorSecondary
              },
              grid: {
                color: surfaceBorder
              }
            },
            y: {
              ticks: {
                color: textColorSecondary
              },
              grid: {
                color: surfaceBorder
              }
            }
          }
        };
      }
    })
  }


  data: any;

  options: any;

  ngOnInit(): void {





















    this.basicData = {
      maintainAspectRatio: false,
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label: 'Receitas',
          backgroundColor: '#91E9B5',
          data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
          label: 'Despesas',
          backgroundColor: '#ED0101',
          data: [28, 48, 40, 19, 86, 27, 90]
        }
      ],

    };


    feather.replace()

    this.isLoading = true
    setTimeout(() => {
      this.isLoading = false
    }, 1000)
  }

  formatMoneyAOA(amount: number): string {
    const roundedAmount = Math.round(amount * 100) / 100;
    const formattedAmount = new Intl.NumberFormat('pt-AO', {
      style: 'currency',
      currency: 'AOA',
      minimumFractionDigits: 2,
    }).format(roundedAmount);

    return formattedAmount;
  }


}
